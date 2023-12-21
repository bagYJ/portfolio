<?php
declare(strict_types=1);

namespace App\Responses\AutoParking;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.autoparking.ParkingList')]
class ParkingList
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0000')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '결과 메세지')]
    public readonly ?string $resultMessage;
    #[OA\Property(description: '결과 코드')]
    public readonly string $resultCode;
    #[OA\Property(description: '주차장 리스트', type: 'array', items: new OA\Items(ParkingLotsList::class))]
    public readonly Collection $parkingLotsList;

    public function __construct(array $response)
    {
        $this->interfaceCode = data_get($response, 'interfaceCode');
        $this->resultMessage = data_get($response, 'resultMessage');
        $this->resultCode = data_get($response, 'resultCode');
        $this->parkingLotsList = collect(data_get($response, 'parkingLotsList'))->map(function (array $list) {
            return new ParkingLotsList($list);
        });
    }
}
