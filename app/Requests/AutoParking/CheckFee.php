<?php
declare(strict_types=1);

namespace App\Requests\AutoParking;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'request.autoparking.CheckFee', description: '주차 비용 조회 요청')]
class CheckFee
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0004')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '차량 번호')]
    public readonly string $plateNumber;
    #[OA\Property(description: '주차장 ID')]
    public readonly string $storeId;
    #[OA\Property(description: '거래 일련번호')]
    public readonly string $txId;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->interfaceCode = data_get($valid, 'interfaceCode');
        $this->plateNumber = data_get($valid, 'plateNumber');
        $this->storeId = data_get($valid, 'storeId');
        $this->txId = data_get($valid, 'txId');
    }
}
