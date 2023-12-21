<?php
declare(strict_types=1);

namespace App\Responses\Parking;

use App\Responses\Response;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.parking.Order')]
class Order extends Response
{
    #[OA\Property(description: '구매번호')]
    public readonly int $bookingUid;
    #[OA\Property(description: '구매일시')]
    public readonly string $createdAt;

    public function __construct(array $response)
    {
        parent::__construct();
        $this->bookingUid = (int)data_get($response, 'bookingUid');
        $this->createdAt = data_get($response, 'createdAt');
    }
}
