<?php
declare(strict_types=1);

namespace App\Responses\AutoParking;

use App\Utils\Code;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.autoparking.CarEnter')]
class CarEnter
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0003')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '결과 메세지')]
    public readonly ?string $resultMessage;
    #[OA\Property(description: '결과 코드')]
    public readonly string $resultCode;
    #[OA\Property(description: '차량 번호')]
    public readonly string $plateNumber;
    #[OA\Property(description: '거래 일련번호')]
    public readonly string $txId;

    public function __construct(array $response)
    {
        $this->interfaceCode = data_get($response, 'interfaceCode');
        $this->resultCode = getenv('RETURN_TRUE');
        $this->resultMessage = Code::message(sprintf('auto-parking.%s', $this->resultCode));
        $this->plateNumber = data_get($response, 'plateNumber');
        $this->txId = data_get($response, 'txId');
    }
}
