<?php
declare(strict_types=1);

namespace App\Responses\Parking;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.parking.OrderList')]
class OrderList
{
    #[OA\Property(description: '구매번호')]
    public readonly int $page;
    #[OA\Property(description: '구매번호')]
    public readonly int $total;
    #[OA\Property(description: '구매번호')]
    public readonly Collection $bookings;

    public function __construct(array $response)
    {
        $this->page = (int)data_get($response, 'page');
        $this->total = (int)data_get($response, 'total');
        $this->bookings = collect(data_get($response, 'bookings'))->map(function (array $booking) {
            return new Booking($booking);
        });
    }
}
