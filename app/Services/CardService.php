<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EnumYN;
use App\Exceptions\OwinException;
use App\Models\GsSaleCard;
use App\Models\GsSaleCardIssueLog;
use App\Models\MemberCard;
use App\Models\MemberDeal;
use App\Models\MemberPointcard;
use App\Models\MemberWallet;
use App\Models\OrderPayment;
use App\Models\ShopOilUnuseCard;
use App\Services\Pg\FdkService;
use App\Services\Pg\KcpService;
use App\Services\Pg\NicepayService;
use App\Services\Pg\UplusService;
use App\Utils\Code;
use App\Utils\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class CardService extends Service
{
    public function regist(int $noUser, array $request, bool $isMain = false): string
    {
        $response = [
            'fdk' => (new FdkService())->request($request),
            'uplus' => (new UplusService())->request($request),
            'nicepay' => (new NicepayService())->request($request),
            'kcp' => (new KcpService())->request($request)
        ];

        $noCard = 10 . (time() + 3000000000) . mt_rand(1000, 9999);
        DB::transaction(function () use ($response, $noUser, $noCard, $isMain) {
            $maxCardSeq = MemberCard::where('no_user', $noUser)->withTrashed()->max('no_seq') ?? 1000;
            $noOrder = $noUser . (time() + 1000000000) . mt_rand(100, 999);
            $cardCorp = $response['fdk']['cd_card_corp'];
            $noCardUser = $response['fdk']['no_card_user'];

            foreach ($response as $pg) {
                if ($pg['result_code'] != '0000') {
                    throw new OwinException(Code::message('P1022'));
                }

                (new OrderPayment([
                    'no_order' => $noOrder,
                    'no_payment' => (time() + 3000000000) . mt_rand(1000, 9999),
                    'no_partner' => Code::conf('billkey.no_partner'),
                    'no_shop' => Code::conf('billkey.no_shop'),
                    'no_user' => $noUser,
                    'cd_pg' => $pg['cd_pg'],
                    'ds_res_order_no' => $pg['ds_res_order_no'],
                    'cd_payment' => '501200',
                    'cd_payment_kind' => '',
                    'cd_payment_status' => '603300',
                    'ds_server_reg' => date('YmdHis'),
                    'ds_res_param' => json_encode($pg['res_param']),
                    'cd_pg_result' => '604100',
                    'ds_res_msg' => $pg['result_msg'],
                    'ds_res_code' => $pg['result_code'],
                    'at_price' => Code::conf('billkey.at_price_zero'),
                    'at_price_pg' => Code::conf('billkey.at_price_zero'),
                ]))->saveOrFail();

                (new MemberCard([
                    'no_user' => $noUser,
                    'no_seq' => ++$maxCardSeq,
                    'cd_card_corp' => '5030' . Code::card(sprintf('500100.%s', $cardCorp)),
                    'no_card' => $noCard,
                    'no_card_user' => $noCardUser,
                    'ds_billkey' => $pg['ds_billkey'],
                    'cd_pg' => $pg['cd_pg'],
                    'yn_credit' => $pg['yn_credit'],
                    'yn_main_card' => $isMain ? 'Y' : 'N'
                ]))->saveOrFail();
            }
        });

        return $noCard;
    }

    public function remove(int $noCard, int $noUser): void
    {
        MemberCard::where('no_user', $noUser)
            ->where('no_card', $noCard)
            ->update([
                'yn_delete' => EnumYN::Y->name,
                'dt_del' => now()
            ]);
    }

    public function gsSaleCard(?array $where, ?string $bandwidthSt = null, ?string $bandwidthEnd = null): Collection
    {
        return GsSaleCard::when(empty($where) === false, function($query) use ($where) {
            $query->where($where);
        })->where(function ($query) use ($bandwidthSt, $bandwidthEnd) {
            if (empty($bandwidthSt) === false) {
                $query->where('id_pointcard', '>=', $bandwidthSt);
            }
            if (empty($bandwidthEnd) === false) {
                $query->where('id_pointcard', '<', $bandwidthEnd);
            }
        })->get();
    }

    public function maxGsPointCard(string $key): GsSaleCard
    {
        return GsSaleCard::max($key);
    }

    public static function upsertGsSalesCard(array $where, array $parameter): void
    {
        GsSaleCard::updateOrCreate($where, $parameter);
    }

    public static function updateGsSalesCard(GsSaleCard $card, array $parameter): void
    {
        $card->update($parameter);
    }

    /**
     * @param int $noUser
     * @param string $cardNumber
     * @param EnumYN $yn
     * @return void
     * @throws Throwable
     */
    public function gsCardLog(int $noUser, string $cardNumber, EnumYN $yn): void
    {
        (new GsSaleCardIssueLog([
            'no_user' => $noUser,
            'id_pointcard' => $cardNumber,
            'ds_issue_status' => $yn->name
        ]))->saveOrFail();
    }

    public function pointCardRemove(int $noUser, int $idPointcard): void
    {
        $pointcard = $this->memberPointCard([
            'no_user' => $noUser,
            'id_pointcard' => $idPointcard
        ])->whenEmpty(function () {
            throw new OwinException(Code::message('SC9999'));
        })->first();

        if ($pointcard->yn_sale_card == EnumYN::Y->name) {
            $pointcard->update(['yn_delete' => EnumYN::Y->name]);
            MemberDeal::where('no_user', $noUser)->update(['yn_pointcard_issue' => EnumYN::N->name]);
        } else {
            $pointcard->delete();
        }
    }

    public function cardList(
        ?int $noUser,
        ?int $noShop = null,
        array $listCdPg = [],
        bool $isUnUseCard = false,
        bool $isCreditOnly = false,
        bool $isDelete = false
    ): Collection {
        $memberCard = new MemberCard();
        if ($listCdPg) {
            $memberCard = $memberCard->whereIn('cd_pg', $listCdPg);
        }

        if ($isUnUseCard && $noShop) {
            $unUseCards = ShopOilUnuseCard::where([
                'no_shop' => $noShop,
                'yn_unuse_status' => 'Y'
            ])->get()->pluck('cd_card_corp')->all();

            $unUseCards = array_unique($unUseCards);
            $memberCard = $memberCard->whereNotIn('cd_card_corp', $unUseCards);
        }

        if ($isCreditOnly) {
            $memberCard = $memberCard->whereRaw("(yn_credit = 'N' OR yn_credit IS NULL)");
        }
        if ($isDelete) {
            $memberCard = $memberCard->whereRaw("(yn_delete = 'N')");
        }

        return $memberCard->where('no_user', $noUser)->select(
            DB::raw('DISTINCT no_card'),
            'no_card_user',
            'cd_card_corp',
            'yn_main_card'
        )->get()
            ->map(function ($collect) {
                $collect->img_card = Common::getImagePath(Code::conf("card_image.{$collect['cd_card_corp']}"), "/data2/card/");
                return $collect;
            });
    }

    public function getCardList(int $noUser): Collection
    {
        $groupCode = CodeService::getGroupCode('503');

        return $this->cardList($noUser)->map(function ($card) use ($groupCode) {
            return [
                'no_card' => $card->no_card,
                'no_card_user' => $card->no_card_user,
                'cd_card_corp' => $card->cd_card_corp,
                'card_corp' => $groupCode->where('no_code', $card->cd_card_corp)->first()->nm_code,
//                'cd_payment_method' => "504100",        // controller 안에서 설정하는 것으로 변경해야 함
            ];
        });
    }

    public function memberPointCard(array $parameter): Collection
    {
        return MemberPointcard::with('promotionDeal')->where($parameter)->get();
    }

    public static function upsertMemberPointcard(array $where, array $parameter): void
    {
        MemberPointcard::updateOrCreate($where, $parameter);
    }

    public function gsSaleCardIssueLogRegist(array $parameter): void
    {
        (new GsSaleCardIssueLog($parameter))->saveOrFail();
    }

    public function pointCard(int $noUser, EnumYN $yn): Model
    {
        return MemberPointcard::with('promotionDeal')->where([
            'no_user' => $noUser,
            'yn_delete' => $yn->name
        ])->get()->whenNotEmpty(function () {
            throw new OwinException(Code::message('SC1110'));
        })->first();
    }

    public static function getCardInfo(
        int $noUser,
        int $noCard = null,
        string $cdPaymentMethod,
        string $cdServicePay = null,
        string $cdPg = null,
        $ynDelete = null
    ) {
        return match ($cdPaymentMethod) {
            '504200' => self::getMemberWalletInfo($noUser, $noCard, $cdServicePay, $cdPg, $ynDelete),
            default => self::getMemberCardInfo($noUser, $noCard, $cdServicePay, $cdPg, $ynDelete)
        };
    }

    private static function getMemberCardInfo(
        int $noUser,
        int $noCard = null,
        string $cdServicePay = null,
        string $cdPg = null,
        string $ynDelete = null
    ) {
        $where = [
            'no_user' => $noUser
        ];

        if ($noCard) {
            $where['no_card'] = $noCard;
        }

        if ($cdServicePay) {
            $where['cd_service_pay'] = $cdServicePay;
        }

        if ($cdPg) {
            $where['cd_pg'] = $cdPg;
        }

        if ($ynDelete) {
            $where['yn_delete'] = $ynDelete;
        }

        return MemberCard::where($where)->select([
            'no_user',
            'cd_card_corp',
            'no_card',
            'no_card_user',
            'ds_pay_passwd',
            'yn_main_card',
            'dt_reg',
            'cd_pg',
            'yn_credit',
            DB::raw('ds_billkey AS ds_paykey')
        ])->first();
    }

    private static function getMemberWalletInfo(
        int $noUser,
        int $noCard = null,
        string $cdServicePay = null,
        string $cdPg = null,
        string $ynDelete = null
    ): Model {
        $where = [
            'no_user' => $noUser
        ];

        if ($noCard) {
            $where['no_card'] = $noCard;
        }

        if ($cdServicePay) {
            $where['cd_service_pay'] = $cdServicePay;
        }

        if ($cdPg) {
            $where['cd_pg'] = $cdPg;
        }

        if ($ynDelete) {
            $where['yn_delete'] = $ynDelete;
        }

        return MemberWallet::where($where)->select([
            'no_user',
            'cd_card_corp',
            'no_card',
            'no_card_user',
            'yn_main_card',
            'dt_reg',
            'cd_pg',
            'yn_credit',
            DB::raw('\'\' AS ds_pay_passwd'),
            DB::raw('tr_id AS ds_paykey')
        ])->first();
    }
}
