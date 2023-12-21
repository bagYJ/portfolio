<?php
declare(strict_types=1);

namespace App\Responses\Parking;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.parking.Site')]
class Site
{
    #[OA\Property(description: '주차장 고유번호')]
    public readonly int $uid;
    #[OA\Property(description: '주차장 이름')]
    public readonly string $name;
    #[OA\Property(description: '주차장 가격정보')]
    public readonly string $price;
    #[OA\Property(description: '주차장 주소')]
    public readonly string $address;
    #[OA\Property(description: '주차장 전화번호')]
    public readonly string $tel;
    #[OA\Property(description: '주차장 운영시간')]
    public readonly string $operationTime;
    #[OA\Property(description: '주차장 위도')]
    public readonly float $lat;
    #[OA\Property(description: '주차장 경도')]
    public readonly float $lon;
    #[OA\Property(description: '유의사항(Markdown)')]
    public readonly string $caution;
    #[OA\Property(description: '할인권 목록', type: 'array', items: new OA\Items(Ticket::class))]
    public readonly Collection $tickets;

    public function __construct(array $response)
    {
        $this->uid = (int)data_get($response, 'uid');
        $this->name = data_get($response, 'name');
        $this->price = data_get($response, 'price');
        $this->address = data_get($response, 'address');
        $this->tel = data_get($response, 'tel');
        $this->operationTime = data_get($response, 'operationTime');
        $this->lat = (float)data_get($response, 'lat');
        $this->lon = (float)data_get($response, 'lon');
        $this->caution = data_get($response, 'caution');
        $this->tickets = collect(data_get($response, 'tickets'))->map(function (array $ticket) {
            return new Ticket($ticket);
        });
    }
}
