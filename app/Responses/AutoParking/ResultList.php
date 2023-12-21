<?php
declare(strict_types=1);

namespace App\Responses\AutoParking;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.autoparking.ResultList')]
class ResultList
{
    #[OA\Property(description: '결과 메세지')]
    public readonly ?string $resultMessage;
    #[OA\Property(description: '결과 코드')]
    public readonly string $resultCode;
    #[OA\Property(description: '차량 번호')]
    public readonly string $plateNumber;

    public function __construct(array $list)
    {
        $this->resultMessage = data_get($list, 'resultMessage');
        $this->resultCode = data_get($list, 'resultCode');
        $this->plateNumber = data_get($list, 'plateNumber');
    }
}
