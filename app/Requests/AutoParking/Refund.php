<?php
declare(strict_types=1);

namespace App\Requests\AutoParking;

use App\Utils\Common;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'request.autoparking.Refund', description: '결제 승인 취소 후 정보 전달')]
class Refund
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0006')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '주차장 ID')]
    public readonly string $storeId;
    #[OA\Property(description: '차량 번호')]
    public readonly string $plateNumber;
    #[OA\Property(description: '거래 일련번호')]
    public readonly string $txId;
    #[OA\Property(description: '승인 취소 처리된 금액: 암호화 필드')]
    public readonly string $cancelPrice;
    #[OA\Property(description: '승인 취소 처리된 시간')]
    public readonly string $cancelDate;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->interfaceCode = data_get($valid, 'interfaceCode');
        $this->storeId = data_get($valid, 'storeId');
        $this->plateNumber = data_get($valid, 'plateNumber');
        $this->txId = data_get($valid, 'txId');
        $this->cancelPrice = Common::encrypt(data_get($valid, 'cancelPrice'), getenv('AUTO_PARKING_IV'), getenv('AUTO_PARKING_ENCRYPT_KEY'));
        $this->cancelDate = data_get($valid, 'cancelDate');
    }
}
