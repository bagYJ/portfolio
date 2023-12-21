<?php
declare(strict_types=1);

namespace App\Responses\Parking;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.parking.OrderSearch')]
class OrderSearch
{
    #[OA\Property(description: '구매내역', type: 'array', items: new OA\Items(Booking::class))]
    public readonly Collection $bookings;

    public function __construct(array $response)
    {
        $this->bookings = collect($response)->map(function (array $booking) {
            return new Booking($booking);
        });
    }
}
