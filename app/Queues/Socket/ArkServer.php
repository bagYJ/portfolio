<?php
declare(strict_types=1);

namespace App\Queues\Socket;

use App\Models\User;
use App\Utils\Code;
use Illuminate\Support\Collection;

class ArkServer extends Socket
{
    protected string $method;
    protected ?string $header;
    protected string $body;

    public function __construct(string $type, string $method, string $body, string $header = null)
    {
        $this->method = $method;
        $this->header = $header;
        $this->body = $body;
        $this->ip = env(sprintf('%s_IP', $type));
        $this->port = (int)env(sprintf('%s_PORT', $type));
    }

    public function init(): string|array
    {
        return $this->{$this->method}();
    }

    public function oilCoupon(): string
    {
        $parameter = [
            chr(2),
            str_pad((string)strlen($this->header . $this->body . chr(3)), 4, '0', STR_PAD_LEFT),
            $this->header,
            $this->body,
            chr(3)
        ];

        return $this->send(implode('', $parameter));
    }

    public function oil(): array
    {
        $response = $this->send($this->body);

        return [
            'id_pointcard' => substr($response, 100, 16),
            'ds_cvc' => substr($response, 116, 3),
            'ds_status_code' => substr($response, 119, 2),
            'ds_status_name' => iconv("EUC-KR", "UTF-8", substr($response, 121, 100)),
            'ds_type_code' => substr($response, 221, 4),
            'ds_type_name' => iconv("EUC-KR", "UTF-8", substr($response, 225, 100)),
            'ds_publisher_code' => substr($response, 325, 4),
            'ds_publisher' => iconv("EUC-KR", "UTF-8", substr($response, 329, 100)),
            'dt_reg' => substr($response, 429, 14),
            'ds_sale_code' => substr($response, 443, 10),
            'ds_sale_name' => iconv("EUC-KR", "UTF-8", substr($response, 453, 30)),
            'ds_expire_date' => substr($response, 483, 8),
            'yn_sale_status' => substr($response, 491, 1),
            'ds_expire_time' => substr($response, 492, 5),
            'at_can_save_total' => substr($response, 497, 15),
            'at_save_amt' => substr($response, 512, 15),
            'at_can_save_amt' => substr($response, 527, 15),
            'result_code' => substr($response, 55, 5)
        ];
    }

    public function card(): array
    {
        $response = $this->send($this->body);

        return [
            'result_code' => substr($response, 55, 5), //응답코드
            'card_status' => substr($response, 100, 1), //처리구분
            'no_card' => substr($response, 106, 16), //GS멤버쉽 카드번호
            'validity' => substr($response, 122, 4), //GS멤버쉽 유효기간(MMYY 없는경우 0000)
            'nm_card' => iconv('EUC-KR', 'UTF-8', substr($response, 126, 20)) //GS멤버쉽 이름
        ];
    }

    public function point(): array
    {
        $response = $this->send($this->body);

        return [
            'result_code' => substr($response, 55, 5), //응답코드
            'point' => substr($response, 116, 15), //
        ];
    }

    public function list(): array
    {
        $response = $this->send($this->body);

        $cardNum = (int)substr($response, 100, 5);
        $cardList = [];
        for ($len = 0; $len < $cardNum; $len++) {
            $cardStart = 105 + (40 * $len);
            $cardEnd = 40;

            $data = substr($response, $cardStart, $cardEnd);
            $cardList[] = [
                'id_pointcard' => substr($data, 0, 16),
                'nm_pointcard' => iconv('EUC-KR', 'UTF-8', substr($data, 20, 20)),
                'yn_sale_card' => 'N',
                'result_code' => substr($response, 55, 5)
            ];
        }

        return $cardList;
    }

    public function orderStatus(): string
    {
        $param = [
            chr(2),
            $this->setByteLength($this->body),
            pack('C', $this->header),
            $this->body,
            chr(3)
        ];
        return $this->send(implode('', $param));
    }

    public static function makeControlPacketDefault(string $type, string $code, User $user): string
    {
        $control = Code::packet(sprintf('%s.%s', $type, $code));

        return self::makePacket(collect([
            [$control['length'], 4, '0', STR_PAD_LEFT],
            [$control['type'], 4, '0', STR_PAD_LEFT],
            [$control['scope'], 2, '0', STR_PAD_LEFT],
            ['0010', 4, ' ', STR_PAD_RIGHT],
            [getenv('GS_COMPANY_CODE'), 4, ' ', STR_PAD_RIGHT],
            [now()->format('Ymd'), 8, '0', STR_PAD_LEFT],
            [now()->format('His'), 6, '0', STR_PAD_LEFT],
            [$user->ds_phone . now()->format('His'), 20, '0', STR_PAD_LEFT],
            ['000', 3, ' ', STR_PAD_RIGHT],
            ['00000', 5, ' ', STR_PAD_RIGHT],
            ['', 40, ' ', STR_PAD_RIGHT],
        ]));
    }

    public static function makeMemberPacket(string $code, User $user, array $agreeResult): string
    {
        return self::makeControlPacketDefault('default', $code, $user)
            . self::makePacket(collect([
                [$user->ds_ci, 128, ' ', STR_PAD_RIGHT],
                [$user->ds_birthday, 8, ' ', STR_PAD_RIGHT],
                [$user->ds_sex == 'M' ? '1' : '2', 1, ' ', STR_PAD_RIGHT],
                [iconv('UTF-8', 'EUC-KR', $user->nm_user), 3, ' ', STR_PAD_RIGHT],
                [self::getPhoneNumber($user->ds_phone), 12, ' ', STR_PAD_RIGHT],
                [self::getPhoneAgency($user->memberDetail->ds_phone_agency), 2, ' ', STR_PAD_RIGHT],
                ['01', 2, ' ', STR_PAD_RIGHT],
                [$user->id_user, 145, ' ', STR_PAD_RIGHT],
                ['G976020001', 10, ' ', STR_PAD_RIGHT],
                [$user->dt_reg->format('YmdHis'), 14, ' ', STR_PAD_RIGHT],
                [$user->dt_reg->format('YmdHis'), 14, ' ', STR_PAD_RIGHT],
                ['02', 2, ' ', STR_PAD_RIGHT],
                [now()->format('YmdHis'), 14, ' ', STR_PAD_RIGHT],
                ['12', 2, ' ', STR_PAD_RIGHT],
                ['01', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[1], 1, ' ', STR_PAD_RIGHT],
                ['02', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[2], 1, ' ', STR_PAD_RIGHT],
                ['09', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[1], 1, ' ', STR_PAD_RIGHT],
                ['71', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[4], 1, ' ', STR_PAD_RIGHT],
                ['81', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[4], 1, ' ', STR_PAD_RIGHT],
                ['72', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[7], 1, ' ', STR_PAD_RIGHT],
                ['82', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[7], 1, ' ', STR_PAD_RIGHT],
                ['83', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[7], 1, ' ', STR_PAD_RIGHT],
                ['61', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[6], 1, ' ', STR_PAD_RIGHT],
                ['73', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[7], 1, ' ', STR_PAD_RIGHT],
                ['93', 2, ' ', STR_PAD_RIGHT],
                ['N', 1, ' ', STR_PAD_RIGHT],
                ['94', 2, ' ', STR_PAD_RIGHT],
                [$agreeResult[3], 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 16, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 36, ' ', STR_PAD_RIGHT]
            ]));
    }

    public static function makeMemberPacketSale(string $code, User $user, string $gsSaleCode, string $cardNumber, array $agreeResult): string
    {
        return self::makeControlPacketDefault('sale', $code, $user)
            . self::makePacket(collect([
                [$gsSaleCode, 10, ' ', STR_PAD_RIGHT],
                [$cardNumber, 16, ' ', STR_PAD_RIGHT],
                [$user->ds_ci, 128, ' ', STR_PAD_RIGHT],
                [$user->ds_birthday, 8, ' ', STR_PAD_RIGHT],
                [$user->ds_sex == 'M' ? '1' : '2', 1, ' ', STR_PAD_RIGHT],
                [iconv('UTF-8', 'EUC-KR', $user->nm_user), 30, ' ', STR_PAD_RIGHT],
                [self::getPhoneNumber($user->ds_phone), 12, ' ', STR_PAD_RIGHT],
                [self::getPhoneAgency($user->memberDetail->ds_phone_agency), 2, ' ', STR_PAD_RIGHT],
                ['01', 2, ' ', STR_PAD_RIGHT],
                [$user->id_user, 145, ' ', STR_PAD_RIGHT],
                ['G976020001', 10, ' ', STR_PAD_RIGHT],
                [$user->dt_reg->format('YmdHis'), 14, ' ', STR_PAD_RIGHT],
                [$user->dt_reg->format('YmdHis'), 14, ' ', STR_PAD_RIGHT],
                ['02', 2, ' ', STR_PAD_RIGHT],
                [now()->format('YmdHis'), 14, ' ', STR_PAD_RIGHT],
                ['12', 2, ' ', STR_PAD_RIGHT],
                ['01', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 1, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['02', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 2, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['09', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 1, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['71', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 4, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['81', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 4, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['72', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 7, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['82', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 7, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['83', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 7, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['61', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 6, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['73', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 7, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['93', 2, ' ', STR_PAD_RIGHT],
                ['N', 1, ' ', STR_PAD_RIGHT],
                ['94', 2, ' ', STR_PAD_RIGHT],
                [data_get($agreeResult, 3, 'N'), 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 2, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 1, ' ', STR_PAD_RIGHT],
                ['', 26, ' ', STR_PAD_RIGHT]
            ]));
    }

    public static function makeCardInfoPacketSale(string $code, User $user, string $cardNumber): string
    {
        return self::makeControlPacketDefault('sale', $code, $user)
            . self::makePacket(collect([
                [$cardNumber, 16, ' ', STR_PAD_RIGHT]
            ]));
    }

    public static function makePointPacket(string $code, User $user, string $pointcard): string
    {
        return self::makeControlPacketDefault('default', $code, $user)
            . self::makePacket(collect([
                [$pointcard, 16, ' ', STR_PAD_LEFT]
            ]));
    }

    public static function makeCardListPacket(string $code, User $user): string
    {
        return self::makeControlPacketDefault('default', $code, $user)
            . self::makePacket(collect([
                [$user->ds_ci, 128, ' ', STR_PAD_RIGHT],
                [$user->ds_birthday, 8, ' ', STR_PAD_RIGHT],
                [$user->ds_sex == 'M' ? '1' : '2', 1, ' ', STR_PAD_RIGHT],
                [iconv('UTF-8', 'EUC-KR', $user->nm_user), 3, ' ', STR_PAD_RIGHT],
                [self::getPhoneNumber($user->ds_phone), 12, ' ', STR_PAD_RIGHT],
                [self::getPhoneAgency($user->memberDetail->ds_phone_agency), 2, ' ', STR_PAD_RIGHT],
                ['01', 2, ' ', STR_PAD_RIGHT],
                ['', 67, ' ', STR_PAD_RIGHT]
            ]));
    }

    private function setByteLength(string $body, int $addLength = 2): string
    {
        $data = str_pad((string)(strlen($body) + $addLength), 8, '0', STR_PAD_LEFT);

        return pack('CC', substr($data, 0, 4), substr($data, 4, 4));
    }

    private static function makePacket(Collection $parameter): string
    {
        return $parameter->map(function ($data) {
            return str_pad((string)$data[0], $data[1], $data[2], $data[3]);
        })->join('');
    }

    private static function getPhoneNumber(string $phone): string
    {
        $arr_ds_phone = explode("-", preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $phone));

        return str_pad($arr_ds_phone[0], 4, ' ', STR_PAD_RIGHT)
            . str_pad($arr_ds_phone[1], 4, ' ', STR_PAD_RIGHT)
            . str_pad($arr_ds_phone[2], 4, ' ', STR_PAD_RIGHT);
    }

    private static function getPhoneAgency(?string $phoneAgency): string
    {
        return match ($phoneAgency) {
            'KTF' => '02',
            'LGT' => '03',
            'KCT' => '04',
            default => '01'
        };
    }
}
