<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Member;

use OpenApi\Attributes as OA;

#[OA\Schema]
class MemberResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '회원번호')]
    public int $no_user;
    #[OA\Property(description: '회원명')]
    public string $nm_user;
    #[OA\Property(description: '회원ID')]
    public string $id_user;
    #[OA\Property(title: 'MainCarInfo', description: '대표차량정보')]
    public MainCarInfo $car_info;
    #[OA\Property(title: 'MainCardInfo', description: '대표카드정보')]
    public MainCardInfo $card_info;

}

#[OA\Schema]
class MainCarInfo
{
    #[OA\Property(description: '차량번호')]
    public string $ds_car_number;
    #[OA\Property(description: '자동주차 등록여부(Y: 등록, N: 미등록)')]
    public string $yn_use_auto_parking;
    #[OA\Property(description: '차량 정보 삭제여부(Y: 삭제, N: 등록)')]
    public string $yn_delete;
    #[OA\Property(description: '유종 코드 (204100: 휘발유, 204200: 경유, 204300: LPG, 204400: 고급 휘발유, 204500: 전기, 204600: 실내등유)')]
    public string $cd_gas_kind;
    #[OA\Property(description: '세차요금구분용-차종(214001: 승용, 214002: SUV/RV, 214003: 승합)')]
    public string $cd_car_kind;
}

#[OA\Schema]
class MainCardInfo
{
    #[OA\Property(description: '카드사 명')]
    public string $cd_card_corp;
    #[OA\Property(description: '사용자카드번호(카드번호뒤4자리)')]
    public string $no_card_user;
}
