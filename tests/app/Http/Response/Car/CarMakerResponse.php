<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Car;

use OpenApi\Attributes as OA;

#[OA\Schema]
class CarMakerResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '제조사 목록', type: 'array', items: new OA\Items(ref: '#/components/schemas/CarMakerList'))]
    public CarMakerList $car_maker_list;
}

#[OA\Schema]
class CarMakerList
{
    #[OA\Property(description: '제조사 코드')]
    public int $no_maker;
    #[OA\Property(description: '제조사 명')]
    public string $nm_maker;
}

