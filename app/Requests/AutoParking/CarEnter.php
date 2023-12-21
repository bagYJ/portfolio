<?php
declare(strict_types=1);

namespace App\Requests\AutoParking;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'request.autoparking.CarEnter', description: '입차 차량 정보 전달')]
class CarEnter
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0003')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '차량 번호')]
    public readonly string $plateNumber;
    #[OA\Property(description: '주차장 ID')]
    public readonly string $storeId;
    #[OA\Property(description: '주차장 이름')]
    public readonly string $storeName;
    #[OA\Property(description: '입차 시각')]
    public readonly string $entryTime;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->interfaceCode = data_get($valid, 'interfaceCode');
        $this->plateNumber = data_get($valid, 'plateNumber');
        $this->storeId = data_get($valid, 'storeId');
        $this->storeName = data_get($valid, 'storeName');
        $this->entryTime = data_get($valid, 'entryTime');
    }
}
