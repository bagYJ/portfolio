<?php
declare(strict_types=1);

namespace App\Responses\AutoParking;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.autoparking.ParkingLotsList')]
class ParkingLotsList
{
    #[OA\Property(description: '주차장 ID')]
    public readonly string $storeId;
    #[OA\Property(description: '주차장 이름')]
    public readonly string $storeName;
    #[OA\Property(description: '주차장 분류: HIPARKP(하이파킹), HIPARKK(하이그린파킹), HIPARKC(하이시티파킹)', enum: ['HIPARKP', 'HIPARKK', 'HIPARKC'])]
    public readonly string $storeCategory;
    #[OA\Property(description: '주차장 주소')]
    public readonly string $storeAddress;
    #[OA\Property(description: '주차장 위도')]
    public readonly string $storeLatitude;
    #[OA\Property(description: '주차장 경도')]
    public readonly string $storeLongitude;
    #[OA\Property(description: '추가 시간')]
    public readonly string $addTime;
    #[OA\Property(description: '추가 요금')]
    public readonly string $addPrice;
    #[OA\Property(description: '평일 운영 방법: 1(24시간제), 2(시간제), 3(휴무), 4(정보 없음)', enum: ['1', '2', '3', '4'])]
    public readonly string $parkBizType;
    #[OA\Property(description: '토요일 운영 방법: 1(24시간제), 2(시간제), 3(휴무), 4(정보 없음)', enum: ['1', '2', '3', '4'])]
    public readonly string $parkSatBizType;
    #[OA\Property(description: '토요일 운영 방법: 1(24시간제), 2(시간제), 3(휴무), 4(정보 없음)', enum: ['1', '2', '3', '4'])]
    public readonly string $parkHolBizType;
    #[OA\Property(description: '평일 운영 시간')]
    public readonly string $parkBizTime;
    #[OA\Property(description: '토요일 운영 시간')]
    public readonly string $parkSatTime;
    #[OA\Property(description: '일요일 운영 시간')]
    public readonly string $parkHolTime;

    public function __construct(array $list)
    {
        $this->storeId = data_get($list, 'storeId');
        $this->storeName = data_get($list, 'storeName');
        $this->storeCategory = data_get($list, 'storeCategory');
        $this->storeAddress = data_get($list, 'storeAddress');
        $this->storeLatitude = data_get($list, 'storeLatitude');
        $this->storeLongitude = data_get($list, 'storeLongitude');
        $this->addTime = data_get($list, 'addTime');
        $this->addPrice = data_get($list, 'addPrice');
        $this->parkBizType = data_get($list, 'parkBizType');
        $this->parkSatBizType = data_get($list, 'parkSatBizType');
        $this->parkHolBizType = data_get($list, 'parkHolBizType');
        $this->parkBizTime = data_get($list, 'parkBizTime');
        $this->parkSatTime = data_get($list, 'parkSatTime');
        $this->parkHolTime = data_get($list, 'parkHolTime');
    }
}
