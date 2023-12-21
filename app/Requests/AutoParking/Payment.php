<?php
declare(strict_types=1);

namespace App\Requests\AutoParking;

use App\Utils\Common;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'request.autoparking.Payment', description: '결제 완료 정보 전달')]
class Payment
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0006')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '거래 일련번호')]
    public readonly string $txId;
    #[OA\Property(description: '주차장 ID')]
    public readonly string $storeId;
    #[OA\Property(description: '주차장 분류: HIPARKP(하이파킹), HIPARKK(하이그린파킹), HIPARKC(하이시티파킹)', enum: ['HIPARKP', 'HIPARKK', 'HIPARKC'])]
    public readonly string $storeCategory;
    #[OA\Property(description: '차량 번호')]
    public readonly string $plateNumber;
    #[OA\Property(description: '승인 금액: 암호화 필드')]
    public readonly string $approvalPrice;
    #[OA\Property(description: '승인 일시')]
    public readonly string $approvalDate;
    #[OA\Property(description: '승인 번호')]
    public readonly string $approvalNumber;
    #[OA\Property(description: '승인 성공/실패 여부: 0(실패), 1(성공)', enum: ['0', '1'])]
    public readonly string $approvalResult;
    #[OA\Property(description: '결과 메세지')]
    public readonly ?string $approvalMessage;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->interfaceCode = data_get($valid, 'interfaceCode');
        $this->txId = data_get($valid, 'txId');
        $this->storeId = data_get($valid, 'storeId');
        $this->storeCategory = data_get($valid, 'storeCategory');
        $this->plateNumber = data_get($valid, 'plateNumber');
        $this->approvalPrice = Common::encrypt((string)data_get($valid, 'approvalPrice'), getenv('AUTO_PARKING_IV'), getenv('AUTO_PARKING_ENCRYPT_KEY'));
        $this->approvalDate = data_get($valid, 'approvalDate');
        $this->approvalNumber = data_get($valid, 'approvalNumber');
        $this->approvalResult = data_get($valid, 'approvalResult');
        $this->approvalMessage = data_get($valid, 'approvalMessage');
    }
}
