<?php

declare(strict_types=1);

namespace Tests\app\Http\Request\Order;

use OpenApi\Attributes as OA;

#[OA\Schema]
class GpsAlramRequest
{
    #[OA\Property(description: '상점번호', nullable: false)]
    public int $no_shop;
    #[OA\Property(description: '주문번호', nullable: false)]
    public string $no_order;
    #[OA\Property(description: '알람타입', enum: [1, 2, 3], nullable: false)]
    public string $cd_alarm_event_type;
    #[OA\Property(description: '위도', nullable: true)]
    public float $at_lat;
    #[OA\Property(description: '경도', nullable: true)]
    public float $at_lng;
    #[OA\Property(description: '거리', nullable: true)]
    public int $at_distance;
    #[OA\Property(description: 'GPS 활성화 상태', nullable: true)]
    public int $at_yn_gps_statuslng;
}
