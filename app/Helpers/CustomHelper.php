<?php

declare(strict_types=1);

use App\Enums\AppType;
use App\Enums\MemberLevel;
use Symfony\Component\Yaml\Yaml;

function parameterReplace(array $parameter): array
{
    $conf = getYml();

    return array_diff_key($parameter, array_flip($conf['parameter_replace_key']));
}

function getYml(string $file = 'conf'): array
{
    return Yaml::parseFile(storage_path(sprintf('/cfg/%s.yml', $file)));
}

function randNum(int $repeat = 1): string
{
    $response = '';
    while ($repeat--) {
        $response .= mt_rand(10000, 40000);
    }

    return $response;
}

function makeDeviceNo(int $no): string
{
    return $no . time() . mt_rand(1000, 9999);
}

function makePaymentNo(): string
{
    return (time() + 3000000000) . mt_rand(1000, 9999);
}

function makeText(string $text, ...$args): string
{
    return sprintf($text, $args);
}

function makeCuRequest(): object
{
    $opts = ['http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: Application/json',
        'timeout' => 1200
    ]];
    $context = stream_context_create($opts);
    $post = file_get_contents('php://input', true, $context);

    return json_decode(base64_decode($post));
}

function getOrderStatus(
    string|int $cdBizKind,
    string $cdOrderStatus,
    ?string $cdPickupStatus = null,
    ?string $cdPaymentStatus = null,
    ?string $parkingStatus = null,
    ?string $cdPgResult = null
): ?array {
    return match (true) {
        in_array($cdBizKind, ['201100', '201200', '201400', '201800']) => match (true) {
            $cdOrderStatus == '601100' => match ($cdPaymentStatus) {
                '603200' => ['800810', '결제실패'],
                default => ['999999', '문의요망']
            },
            $cdOrderStatus == '601200' => match ($cdPaymentStatus) {
                '603200' => ['800810', '결제실패'],
                '603300' => match ($cdPickupStatus) {
                    '602200' => ['800140', '준비중'],
                    '602300' => ['800150', '픽업가능'],
                    '602400' => ['800410', '픽업완료'],
                    '602900' => ['800440', '픽업미처리'],
                    default => ['800400', '주문완료'],
                },
                default => ['999999', '문의요망']
            },
            $cdOrderStatus == '601900' => match ($cdPaymentStatus == '603900' && $cdPickupStatus == '602400') {
                true => ['800910', '회원취소'],
                default => ['800930', '관리자취소']
            },
            $cdOrderStatus == '601950' => match ($cdPaymentStatus == '603900' && $cdPickupStatus == '602400') {
                true => ['800920', '매장취소'],
                default => ['800930', '관리자취소']
            },
            $cdOrderStatus == '601999' => ['800930', '관리자취소'],
            default => ['999999', '문의요망'],
        },
        $cdBizKind == '201300' => match (true) {
            $cdOrderStatus == '601200' => match ($cdPaymentStatus) {
                '603100' => match ($cdPickupStatus) {
                    '602200' => ['800100', '주유예약'],
                    '602300' => ['800110', '주유승인'],
                    '602350' => ['800120', '주유시작'],
                    '602400' => ['800810', '결제실패'],
                    '602990' => ['800990', '자동취소'],
                    default => ['999999', '문의요망']
                },
                '603200' => ['800810', '결제실패'],
                '603300' => match ($cdPickupStatus) {
                    '602400' => ['800400', '주유완료'],
                    default => ['999999', '문의요망']
                },
                default => ['999999', '문의요망']
            },
            $cdOrderStatus == '601900' => match ($cdPaymentStatus == '603900' && $cdPickupStatus == '602400') {
                true => match ($cdPgResult) {
                    '604940' => ['800800', '한도부족'],
                    default => ['800940', '예약취소']
                },
                default => ['999999', '문의요망']
            },
            default => ['999999', '문의요망'],
        },
        $cdBizKind == '201500' => match ($cdOrderStatus) {
            '601100' => ['800170' , '입차완료'],
            '601200' => match ($parkingStatus) {
                'WAIT' => ['800500', '사용전'],
                'CANCELED' => ['800940', '예약취소'],
                'USED' => ['800400', '사용완료'],
                'EXPIRED' => ['800970', '주차권만료'],
                default => ['800250', '결제완료']
            },
            '601900' => match ($cdPaymentStatus) {
                '603200' => ['800810', '결제실패'],
                default => ['800940', '예약취소'],
            },
            '601999' => match ($cdPaymentStatus) {
                '603900' => match ($parkingStatus) {
                    'EXPIRED' => ['800980', '만료일취소'],
                    default => ['800930', '운영자결제취소']
                },
                default => ['800930', '운영자결제취소'],
            },
            default => ['999999', '문의요망'],
        },
        $cdBizKind == '201600' => match ($cdOrderStatus) {
            '601200' => match ($cdPickupStatus) {
                '602100' => match ($cdPaymentStatus) {
                    '603200' => ['800810', '결제실패'],
                    '603300' => ['800400', '세차예약'],
                    default => ['999999', '문의요망']
                },
                '602300' => ['800250', '세차요청'],
                '602400' => match ($cdPaymentStatus) {
                    '603200' => ['800810', '결제실패'],
                    '603300' => ['800400', '세차완료'],
                    default => ['999999', '문의요망']
                },
                '602990' => ['800990', '자동취소'],
                default => ['999999', '문의요망']
            },
            '601900' => ['800940', '예약취소'],
            '601950' => match ($cdPickupStatus) {
                '602400' => ['800920', '매장취소'],
                '602980' => ['800960', '운영중단취소'],
                default => ['999999', '문의요망']
            },
            '601999' => ['800930', '운영자결제취소'],
            default => ['999999', '문의요망'],
        },
        default => ['999999', '문의요망']
    };
}

function makeNoOrderUser($no): string
{
    return substr($no, -7);
}

function between(int $start, int $end, int|float $num): bool
{
    return ($num >= $start && $num <= $end);
}

function getAppType(): AppType|null
{
    return match (empty(getallheaders()['app-type'])) {
        true => AppType::OWIN,
        default => AppType::case(getallheaders()['app-type'])
    };
}

function getMemberLevel(): MemberLevel
{
    return match (empty(getallheaders()['app-type'])) {
        true => MemberLevel::OWIN,
        default => MemberLevel::case(getallheaders()['app-type'])
    };
}

/**
 * DbToArray:: DB select 후 result 상태의 값을 Array 상태로 전환
 *
 * @param object $str
 * @return array
 */
function DbToArray($str)
{
    $jsonEncode = json_encode($str);
    return json_decode($jsonEncode, true);
}
