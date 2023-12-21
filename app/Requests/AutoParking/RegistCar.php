<?php
declare(strict_types=1);

namespace App\Requests\AutoParking;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'request.autoparking.RegistCar', description: '자동 결제 차량 등록/해제')]
class RegistCar
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0001')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '차량 정보', type: 'array', items: new OA\Items(CarList::class))]
    public readonly Collection $carList;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->interfaceCode = data_get($valid, 'interfaceCode');
        $this->carList = collect(data_get($valid, 'carList'))->map(function (array $car) {
            return new CarList($car);
        });
    }
}
