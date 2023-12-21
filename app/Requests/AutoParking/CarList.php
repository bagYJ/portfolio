<?php
declare(strict_types=1);

namespace App\Requests\AutoParking;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'request.autoparking.CarList', description: '차량 정보')]
class CarList
{
    #[OA\Property(description: '차량 번호')]
    public readonly string $plateNumber;
    #[OA\Property(description: '등록/해지 여부: 0(해제), 1(등록)', enum: ['0', '1'])]
    public readonly string $regType;

    public function __construct(array $car)
    {
        $this->plateNumber = data_get($car, 'plateNumber');
        $this->regType = data_get($car, 'regType');
    }
}
