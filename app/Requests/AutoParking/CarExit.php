<?php
declare(strict_types=1);

namespace App\Requests\AutoParking;

use App\Utils\Common;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'request.autoparking.CarExit', description: '출차 차량 정보 전달')]
class CarExit
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0005')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '차량 번호')]
    public readonly string $plateNumber;
    #[OA\Property(description: '주차장 분류: HIPARKP(하이파킹), HIPARKK(하이그린파킹), HIPARKC(하이시티파킹)', enum: ['HIPARKP', 'HIPARKK', 'HIPARKC'])]
    public readonly string $storeCategory;
    #[OA\Property(description: '주차장 ID')]
    public readonly string $storeId;
    #[OA\Property(description: '입차 시간')]
    public readonly string $entryTime;
    #[OA\Property(description: '출차 시간')]
    public readonly string $exitTime;
    #[OA\Property(description: '거래 일련번호')]
    public readonly string $txId;
    #[OA\Property(description: '최종 결제 요금 (원): 암호화 필드, 해당 금액으로 결제 진행, 0원일 경우 빈 값(’’)으로 전송')]
    public readonly ?string $paymentFee;
    public readonly ?string $encryptFee;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->interfaceCode = data_get($valid, 'interfaceCode');
        $this->plateNumber = data_get($valid, 'plateNumber');
        $this->storeCategory = data_get($valid, 'storeCategory');
        $this->storeId = data_get($valid, 'storeId');
        $this->entryTime = data_get($valid, 'entryTime');
        $this->exitTime = data_get($valid, 'exitTime');
        $this->txId = data_get($valid, 'txId');
        $this->paymentFee = Common::decrypt(data_get($valid, 'paymentFee'), getenv('AUTO_PARKING_IV'), getenv('AUTO_PARKING_ENCRYPT_KEY'));
        $this->encryptFee = data_get($valid, 'paymentFee');
    }
}
