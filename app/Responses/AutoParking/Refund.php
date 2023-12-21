<?php
declare(strict_types=1);

namespace App\Responses\AutoParking;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.autoparking.Refund')]
class Refund
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0007')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '결과 메세지')]
    public readonly ?string $resultMessage;
    #[OA\Property(description: '결과 코드')]
    public readonly string $resultCode;

    public function __construct(array $response)
    {
        $this->interfaceCode = data_get($response, 'interfaceCode');
        $this->resultMessage = data_get($response, 'resultMessage');
        $this->resultCode = data_get($response, 'resultCode');
    }
}
