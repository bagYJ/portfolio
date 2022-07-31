<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Car;

use OpenApi\Attributes as OA;


#[OA\Schema]
class KindByCarResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '제조사 목록', type: 'array', items: new OA\Items(ref: '#/components/schemas/KindByCarList'))]
    public KindByCarList $kind_by_car_list;
}

#[OA\Schema]
class KindByCarList
{
    #[OA\Property(description: '차량 정보 시퀀스')]
    public int $seq;
    #[OA\Property(description: '차종')]
    public string $ds_kind;
}
