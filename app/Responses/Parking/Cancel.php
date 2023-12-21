<?php
declare(strict_types=1);

namespace App\Responses\Parking;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.parking.Cancel')]
class Cancel
{
    #[OA\Property(description: '구매번호')]
    public readonly int $bookingUid;
    #[OA\Property(description: '취소일시')]
    public readonly string $canceledAt;

    public function __construct(array $response)
    {
        $this->bookingUid = (int)data_get($response, 'bookingUid');
        $this->canceledAt = data_get($response, 'canceledAt');
    }
}
