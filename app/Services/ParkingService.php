<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\MobilXException;
use App\Exceptions\OwinException;
use App\Models\ParkingOrderList;
use App\Models\ParkingSite;
use App\Models\ParkingSiteImage;
use App\Models\ParkingSiteTicket;
use App\Utils\Code;
use App\Utils\Parking;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ParkingService
{
    /**
     * 주차장 정보 조회
     * @param float $radius 반경
     * @param float $lat latitude
     * @param float $lng longitude
     * @return Collection
     */
    public static function gets(float $radius, float $lat, float $lng): Collection
    {
        return ParkingSite::select([
            'parking_site.*',
            DB::raw(
                sprintf(
                    '(6371 * ACOS(COS(RADIANS(%1$s)) * COS(RADIANS(at_lat)) * COS(RADIANS(at_lng) -RADIANS(%2$s)) + SIN(RADIANS(%1$s)) * SIN(RADIANS(at_lat)))) AS distance',
                    $lat,
                    $lng
                )
            )
        ])->with([
            'parkingSiteImages',
            'parkingSiteTickets'
        ])->having('distance', '<=', $radius)->orderBy('distance')->get();
    }

    /**
     * 주차장 단일 조회
     * @param array $parameter
     * @return Builder|Model|object|null
     */
    public static function get(array $parameter)
    {
        $parkingInfo = ParkingSite::where($parameter)->first();
        if ($parkingInfo && $parkingInfo['ds_type'] == 'WEB') {
            $parking = Parking::getParkingSite($parkingInfo['id_site']);
            if ($parking) {
                self::updateOrCreate($parkingInfo['no_site'], $parking);
            }
        }

        return ParkingSite::with([
            'parkingSiteImages',
            'parkingSiteTickets'
        ])->where($parameter)->get()->whenEmpty(function () {
            throw new OwinException(Code::message('M1304'));
        })->first();
    }

    /**
     * 구입 가능 티켓 조회
     * @param string $idSite
     * @param int $noProduct
     * @return ParkingSiteTicket|null
     * @throws GuzzleException
     * @throws OwinException
     */
    public static function getActiveTicket(string $idSite, int $noProduct): ?ParkingSiteTicket
    {
        $parkingInfo = ParkingSite::where([
            'id_site' => $idSite
        ])->first();
        if ($parkingInfo['no_parking_site']) {
            $parking = Parking::getParkingSite($idSite);
            if ($parking) {
                self::updateOrCreate($parkingInfo['no_site'], $parking);
            }
        }

        $ticket = ParkingSiteTicket::where([
            'id_site' => $idSite,
            'no_product' => $noProduct,
            'cd_selling_status' => 'AVAILABLE'
        ])->with(['parkingSite.parkingSiteImages'])->first();

        if ($ticket) {
            if ($ticket['cd_selling_status'] === 'NOT_YET_TIME') {
                throw new OwinException(Code::message('1003'));
            } elseif ($ticket['cd_selling_status'] === 'SOLD_OUT') {
                throw new OwinException(Code::message('1004'));
            }
            $sellingDays = $ticket['ds_selling_days'];
            if ($sellingDays) {
                $sellingDays = explode(',', $sellingDays);
                if (!in_array(date('w'), $sellingDays)) {
                    throw new OwinException(Code::message('1003'));
                }
            }
            return $ticket;
        }

        throw new OwinException(Code::message('1007'));
    }

    /**
     * 주차 주문번호 생성
     * @param int $noSite
     * @return int
     */
    public static function generateNoOrder(int $noSite)
    {
        $data = ParkingOrderList::select([
            DB::raw(
                "CONCAT( DATE_FORMAT(NOW(),'%y%m%d'),'" . $noSite . "', LPAD(IFNULL(COUNT(no_order)+1,'1'),4,'0')) AS no_order"
            )
        ])->where([
            ['no_site', '=', $noSite],
            ['dt_reg', '>', DB::raw("CURDATE()")]
        ])->first();
        return $data['no_order'].mt_rand(100, 999);
    }

    /**
     * 이전 주문정보 조회
     *
     * @param int        $noUser
     * @param array|null $operate
     * @param array|null $notIn
     *
     * @return mixed
     */
    public function ordering(int $noUser, ?array $operate = null, ?array $notIn = null): Collection
    {
        return ParkingOrderList::where('no_user', $noUser)
            ->where(function ($query) use ($operate, $notIn) {
                if (empty($operate) === false) {
                    foreach ($operate as $key => $value) {
                        $query->where($key, $value[0], $value[1]);
                    }
                }
                if (empty($notIn) === false) {
                    foreach ($notIn as $key => $value) {
                        $query->whereNotIn($key, $value);
                    }
                }
            })->get();
    }

    /**
     * 주문내역 업데이트
     * @param array $where
     * @param array $update
     * @return void
     */
    public static function updateParkingOrder(array $where, array $update): void
    {
        ParkingOrderList::where($where)->update($update);
    }

    /**
     * 회원 주문내역 조회
     * @param array $where
     * @param int|null $offset
     * @param int|null $size
     * @return array
     * @throws OwinException
     */
    public static function getOrderList(array $where = [], ?array $operate = null, ?array $whereIn = null, ): array
    {
        $data = new ParkingOrderList();
        $uids = new ParkingOrderList();
        if ($where) {
            $data = $data->where($where);
            $uids = $uids->where($where);
        }
        if (empty($whereIn) == false) {
            foreach ($whereIn as $key => $value) {
                $data = $data->whereIn($key, $value);
                $uids = $uids->whereIn($key, $value);
            }
        }

        $count = $data->count();
        $data = $data->orderByDesc('dt_reg');

        $dataHash = [];
        $uids = array_chunk($uids->where('cd_parking_status', 'WAIT')->get()->pluck('no_booking_uid')->all(), 10);
        foreach ($uids AS $uid) {
            $bookingData = Parking::getTicketByIds($uid);
            if ($bookingData) {
                foreach ($bookingData as $booking) {
                    $dataHash[$booking['uid']] = $booking;
                }
            }
        }

        DB::transaction(function () use ($data, $dataHash) {
            $rows = $data->get();
            foreach ($rows as $row) {
                if (isset($dataHash[$row['booking_uid']])) {
                    ParkingOrderList::where([
                        'no_order' => $row['no_order']
                    ])->update([
                        'cd_parking_status' => $dataHash[$row['booking_uid']]['status'] ?? null,
                        'ds_user_parking_reserve_time' => $dataHash[$row['booking_uid']]['reserveTime'] ?? null,
                        'dt_user_parking_used' => $dataHash[$row['booking_uid']]['usedAt'] ?? null,
                        'dt_user_parking_canceled' => $dataHash[$row['booking_uid']]['canceledAt'] ?? null,
                        'dt_user_parking_expired' => $dataHash[$row['booking_uid']]['expiredAt'] ?? null,
                    ]);
                }
            }
        });

        return [
            'count' => $count,
            'rows' => $data->with([
                'parkingSite',
                'ticket',
                'user',
            ])->get()
        ];
    }

    public static function getAutoParkingOrderInfo(array $parameter, string $interfaceCode = null): ParkingOrderList
    {
        return ParkingOrderList::with(['autoParking', 'carInfo'])->where($parameter)->get()
            ->whenEmpty(function () use ($interfaceCode) {
                if ($interfaceCode) {
                    throw new MobilXException($interfaceCode, 9011);
                }
                throw new OwinException(Code::message('AP9011'));
        })->first();
    }

    public static function getOrderInfo($noUser, $noOrder): ParkingOrderList
    {
        return ParkingOrderList::where([
            'no_user' => $noUser,
            'no_order' => $noOrder
        ])->with([
            'parkingSite',
            'autoParking',
            'ticket',
        ])->get()->whenEmpty(function () {
            throw new OwinException(Code::message('P2120'));
        })->map(function ($item) {
            list($item->cd_status, $item->nm_status) = getOrderStatus(
                cdBizKind: '201500',
                cdOrderStatus: $item->cd_order_status,
                cdPickupStatus: $item->cd_pickup_status,
                cdPaymentStatus: $item->cd_payment_status,
                cdPgResult: $item->cd_pg_result
            );

            return $item;
        })->first();
    }

    /**
     * 주차장 정보 조회 및 데이터 업데이트
     * @param int $noSite
     * @param array $parking
     * @return mixed
     */
    public static function updateOrCreate(int $noSite, array $parking)
    {
        $parkingData = [
            'nm_shop' => $parking['name'],
            'ds_option_tag' => isset($parking['optionTag']) ? implode(',', $parking['optionTag']) : null,
            'at_price' => isset($parking['price']) ?: 0,
            'ds_price_info' => $parking['priceInfo'],
            'ds_time_info' => $parking['timeInfo'],
            'ds_tel' => $parking['tel'],
            'ds_info' => $parking['info'],
            'at_lat' => $parking['lat'],
            'at_lng' => $parking['lon'],
            'ds_address' => $parking['address'],
            'ds_operation_time' => $parking['operationTime'],
            'ds_caution' => $parking['caution'] ?? null,
        ];

        $imageRows = [];
        if (isset($parking['picture']) && count($parking['picture'])) {
            foreach ($parking['picture'] as $picture) {
                $imageRows[] = [
                    'no_parking_site' => $noSite,
                    'ds_image_url' => $picture
                ];
            }
        }
        $ticketRows = [];
        if (isset($parking['tickets']) && count($parking['tickets'])) {
            foreach ($parking['tickets'] as $ticket) {
                $ticketRows[] = [
                    'no_product' => $ticket['uid'],
                    'no_parking_site' => $noSite,
                    'nm_product' => $ticket['title'],
                    'cd_ticket_type' => $ticket['ticketType'],
                    'cd_ticket_day_type' => $ticket['ticketDayType'],
                    'ds_parking_start_time' => $ticket['parkingStartTime'],
                    'ds_parking_end_time' => $ticket['parkingEndTime'],
                    'ds_selling_days' => implode(',', $ticket['sellingDays']),
                    'ds_selling_start_Time' => $ticket['sellingStartTime'],
                    'ds_selling_end_time' => $ticket['sellingEndTime'],
                    'at_price' => isset($ticket['price']) ? $ticket['price'] : 0,
                    'cd_selling_status' => $ticket['sellingStatus'],
                ];
            }
        }
        return DB::transaction(function () use ($noSite, $parkingData, $imageRows, $ticketRows) {
            ParkingSite::updateOrCreate(['no_site' => $noSite], $parkingData);
            ParkingSiteImage::where('no_parking_site', $noSite)->delete();
            ParkingSiteImage::insert($imageRows);
            ParkingSiteTicket::upsert($ticketRows, ['no_product', 'no_parking_site'], [
                'nm_product',
                'cd_ticket_type',
                'cd_ticket_day_type',
                'ds_parking_start_time',
                'ds_parking_end_time',
                'ds_selling_days',
                'ds_selling_start_time',
                'ds_selling_end_time',
                'at_price',
                'cd_selling_status'
            ]);
            return ParkingSite::where('no_site', $noSite)->with(['parkingSiteImages', 'parkingSiteTickets'])->first();
        });
    }

    public static function checkPayment(int $noUser)
    {
        return ParkingOrderList::where([
            ['no_user', '=', $noUser],
            ['id_auto_parking', '!=', null], //자동결제 주차인 것
            ['dt_exit_time', '!=', null], //입차 상태인 것
            ['cd_pg_result', '!=', '604100'], //pg 승인 구분이 정상이 아닌 것
        ])->with(['autoParking'])->get()->map(function ($collect) {
            return [
                'no_order' => $collect->no_order,
                'nm_order' => $collect->nm_order,
                'no_site' => $collect->no_site,
                'nm_shop' => $collect->autoParking?->nm_shop,
                'ds_car_number' => $collect->ds_car_number,
                'dt_entry_time' => Carbon::parse($collect->dt_entry_time)->format('Y-m-d H:i:s'),
                'dt_exit_time' => Carbon::parse($collect->dt_exit_time)->format('Y-m-d H:i:s'),
                'parking_time' => $collect->dt_entry_time->diff($collect->dt_exit_time)->format('%H시간 %I분'),
                'at_price' => $collect->at_price,
                'cd_card_corp' => $collect->cd_card_corp,
                'card_corp' => CodeService::getCode($collect->cd_card_corp)->nm_code,
                'no_card_user' => $collect->no_card_user,
            ];
        })->first();
    }
}
