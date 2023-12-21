<?php
declare(strict_types=1);

namespace App\Responses\AutoParking;

use App\Utils\Common;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.autoparking.CheckFee')]
class CheckFee
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0004')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '결과 코드')]
    public readonly string $resultCode;
    #[OA\Property(description: '결과 메세지')]
    public readonly ?string $resultMessage;
    #[OA\Property(description: '주차장 ID')]
    public readonly string $storeId;
    #[OA\Property(description: '차량 번호')]
    public readonly string $plateNumber;
    #[OA\Property(description: '거래 일련번호')]
    public readonly string $txId;
    #[OA\Property(description: '결제 예정 금액: 암호화 필드')]
    public readonly int $paymentFee;
    #[OA\Property(description: '주차 된 시간: 단위 - (분), 숫자만 입력')]
    public readonly string $parkingTime;

    public function __construct(array $response)
    {
        $this->interfaceCode = data_get($response, 'interfaceCode');
        $this->resultCode = data_get($response, 'resultCode');
        $this->resultMessage = data_get($response, 'resultMessage');
        $this->storeId = data_get($response, 'storeId');
        $this->plateNumber = data_get($response, 'plateNumber');
        $this->txId = data_get($response, 'txId');
        $this->paymentFee = Common::decrypt(data_get($response, 'paymentFee'), getenv('AUTO_PARKING_IV'), getenv('AUTO_PARKING_ENCRYPT_KEY'));
        $this->parkingTime = data_get($response, 'parkingTime');
    }
}
