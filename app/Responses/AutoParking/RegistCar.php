<?php
declare(strict_types=1);

namespace App\Responses\AutoParking;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.autoparking.RegistCar')]
class RegistCar
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0001')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '결과', type: 'array', items: new OA\Items(ResultList::class))]
    public readonly Collection $resultList;

    public function __construct(array $response)
    {
        $this->interfaceCode = data_get($response, 'interfaceCode');
        $this->resultList = collect(data_get($response, 'resultList'))->map(function (array $list) {
            return new ResultList($list);
        });
    }
}
