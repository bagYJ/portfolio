<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Parking;

use OpenApi\Attributes as OA;

#[OA\Schema]
class ParkingSiteGets
{
    #[OA\Property(description: '성공 여부', example: true)]
    public bool $result;
    #[OA\Property(description: 'row 수', example: 100)]
    public bool $count;
    #[OA\Property(description: '상점 리스트', type: 'array', items: new OA\Items('#/components/schemas/ParkingSite'))]
    public ParkingSite $rows;
}

#[OA\Schema]
class ParkingSite
{
    #[OA\Property(description: '주차장 번호')]
    public int $no_site;
    #[OA\Property(description: '주차장명')]
    public string $nm_site;
    #[OA\Property(description: '주차장 정보 태그 (,로 구분)')]
    public string $option_tag;
    #[OA\Property(description: '시간당 요금(참고용 정보)')]
    public int $price;
    #[OA\Property(description: '주차장 상세 요금(참고용 정보)')]
    public string $price_info;
    #[OA\Property(description: '주차장 시간 정보')]
    public string $time_info;
    #[OA\Property(description: '주차장 전화번호')]
    public string $ds_tel;
    #[OA\Property(description: '주차장 안내정보')]
    public string $info;
    #[OA\Property(description: '주차장 위도')]
    public float $at_lat;
    #[OA\Property(description: '주차장 경도')]
    public float $at_lng;
    #[OA\Property(description: '주차장 주소')]
    public string $ds_address;
    #[OA\Property(description: '운영 시간')]
    public string $operation_time;
    #[OA\Property(description: '유의사항 (markdown)')]
    public string $caution;
    #[OA\Property(description: '주차장 이미지', type: 'array', items: new OA\Items('#/components/schemas/ParkingSiteImage'))]
    public ParkingSiteImage $parkingSiteImage;
    #[OA\Property(description: '주차장 상품', type: 'array', items: new OA\Items('#/components/schemas/ParkingSiteTicket'))]
    public ParkingSiteTicket $parkingSiteTicket;
}

#[OA\Schema]
class ParkingSiteImage
{
    #[OA\Property(description: '주차장 번호')]
    public int $no_site;
    #[OA\Property(description: '상점번호')]
    public string $image_url;
}

#[OA\Schema]
class ParkingSiteTicket
{
    #[OA\Property(description: '상품 고유번호')]
    public int $no_product;
    #[OA\Property(description: '주차장 번호')]
    public int $no_site;
    #[OA\Property(description: '할인권명')]
    public string $nm_product;
    #[OA\Property(description: '할인권 종류 코드')]
    public int $ticket_type;
    #[OA\Property(description: '할인권 요일 코드')]
    public int $ticket_day_type;
    #[OA\Property(description: '주차 가능 시간(시작)')]
    public string $parking_start_time;
    #[OA\Property(description: '주차 종료 시간(종료)')]
    public string $parking_end_time;
    #[OA\Property(description: '구매 가능 요일 (,로 구분)')]
    public string $selling_days;
    #[OA\Property(description: '구매 가능 시간(시작)')]
    public string $selling_start_time;
    #[OA\Property(description: '구매 가능 시간(종료)')]
    public string $selling_end_time;
    #[OA\Property(description: '금액')]
    public int $price;
    #[OA\Property(description: "구입 상태('AVAILABLE','NOT_YET_TIME','SOLD_OUT')")]
    public string $selling_status;
}



