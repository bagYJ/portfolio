<?php

namespace Tests\app\Http\Response\Search;

use OpenApi\Attributes as OA;

#[OA\Schema]
class RadiusSingle
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '차량 유종')]
    public string $cd_gas_kind;
    #[OA\Property(description: '차량 종류')]
    public string $nm_car_kind;
    #[OA\Property(description: '매장리스트', type: 'array', items: new OA\Items('#/components/schemas/RadiusSingleList'))]
    public RadiusSingleList $list;
}

#[OA\Schema]
class RadiusSingleList
{
    #[OA\Property(description: '브랜드번호')]
    public int $no_partner;
    #[OA\Property(description: '상점번호')]
    public int $no_shop;
    #[OA\Property(description: '상점명')]
    public string $nm_shop;
    #[OA\Property(description: '상점 전화번호')]
    public string $ds_tel;
    #[OA\Property(description: '매장메세지')]
    public string $ds_event_msg;
    #[OA\Property(description: '운영시작시간')]
    public string $ds_open_time;
    #[OA\Property(description: '운영종료시간')]
    public string $ds_close_time;
    #[OA\Property(description: '리뷰평점')]
    public float $at_grade;
    #[OA\Property(description: '우편번호')]
    public string $at_post;
    #[OA\Property(description: '주소')]
    public string $ds_address;
    #[OA\Property(description: '나머지 주소')]
    public string $ds_address2;
    #[OA\Property(description: '매장 위도')]
    public string $at_lat;
    #[OA\Property(description: '매장 경도')]
    public string $at_lng;
    #[OA\Property(description: '매장 pin 위도')]
    public string $at_lat_shop;
    #[OA\Property(description: '매장 pin 경도')]
    public string $at_lng_shop;
    #[OA\Property(description: '매장 뷰')]
    public string $ct_view;

    #[OA\Property(description: '배경이미지')]
    public string $ds_image_bg;
    #[OA\Property(description: '이미지1')]
    public string $ds_image1;
    #[OA\Property(description: '이미지2')]
    public string $ds_image2;
    #[OA\Property(description: '이미지3')]
    public string $ds_image3;
    #[OA\Property(description: '이미지4')]
    public string $ds_image4;
    #[OA\Property(description: '이미지5')]
    public string $ds_image5;
    #[OA\Property(description: '이미지6')]
    public string $ds_image6;
    #[OA\Property(description: '이미지7')]
    public string $ds_image7;
    #[OA\Property(description: '이미지8')]
    public string $ds_image8;
    #[OA\Property(description: '이미지9')]
    public string $ds_image9;
    #[OA\Property(description: '이미지10')]
    public string $ds_image10;
    #[OA\Property(description: '픽업이미지1')]
    public string $ds_image_pick1;
    #[OA\Property(description: '픽업이미지2')]
    public string $ds_image_pick2;
    #[OA\Property(description: '픽업이미지3')]
    public string $ds_image_pick3;
    #[OA\Property(description: '픽업이미지4')]
    public string $ds_image_pick4;
    #[OA\Property(description: '픽업이미지5')]
    public string $ds_image_pick5;
    #[OA\Property(description: '매장앱주차이미지')]
    public string $ds_image_parking;
    #[OA\Property(description: '브랜드 pin 경로')]
    public string $ds_pin;
    #[OA\Property(description: '브랜드 BI 경로')]
    public string $ds_bi;
    #[OA\Property(description: '업종상세')]
    public string $cd_biz_kind_detail;
}
