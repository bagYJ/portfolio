<?php
declare(strict_types=1);

namespace App\Responses\Parking;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.parking.Ticket')]
class Ticket
{
    #[OA\Property(description: '고유번호')]
    public readonly int $uid;
    #[OA\Property(description: '할인권명')]
    public readonly string $title;
    #[OA\Property(description: '할인권 종류 코드\n* 1 - 시간권\n* 2 - 야간권\n* 3 - 당일권\n* 4 - 2일권\n* 5 - 3일권\n* 6 - 4일권\n* 7 - 5일권\n* 8 - 6일권\n* 9 - 7일권')]
    public readonly int $ticketType;
    #[OA\Property(description: '할인권 요일 코드\n* 1 - 평일\n* 2 - 휴일\n* 3 - 평일,휴일\n* 4 - 금요일\n* 5 - 토요일\n* 6 - 일요일\n* 7 - 평일(월~목)\n* 8 - 휴일(금~일)')]
    public readonly int $ticketDayType;
    #[OA\Property(description: '주차가능시간(시작)')]
    public readonly string $parkingStartTime;
    #[OA\Property(description: '주차가능시간(종료)')]
    public readonly string $parkingEndTime;
    #[OA\Property(description: '구매가능 요일 Array\n* 0 - 일요일\n* 1 - 월요일\n* 2 - 화요일\n* 3 - 수요일\n* 4 - 목요일\n* 5 - 금요일\n* 6 - 토요일', items: new OA\Items(type: 'integer'))]
    public readonly array $sellingDays;
    #[OA\Property(description: '구매가능시간(시작)')]
    public readonly string $sellingStartTime;
    #[OA\Property(description: '구매가능시간(종료)')]
    public readonly string $sellingEndTime;
    #[OA\Property(description: '판매가격')]
    public readonly int $price;
    #[OA\Property(description: '매가능 상태\n* AVAILABLE - 구매가능\n* NOT_YET_TIME - 구매가능시간아님\n* SOLD_OUT - 매진')]
    public readonly string $sellingStatus;

    public function __construct(array $ticket)
    {
        $this->uid = (int)data_get($ticket, 'uid');
        $this->title = data_get($ticket, 'title');
        $this->ticketType = (int)data_get($ticket, 'ticketType');
        $this->ticketDayType = (int)data_get($ticket, 'ticketDayType');
        $this->parkingStartTime = data_get($ticket, 'parkingStartTime');
        $this->parkingEndTime = data_get($ticket, 'parkingEndTime');
        $this->sellingDays = data_get($ticket, 'sellingDays');
        $this->sellingStartTime = data_get($ticket, 'sellingStartTime');
        $this->sellingEndTime = data_get($ticket, 'sellingEndTime');
        $this->price = (int)data_get($ticket, 'price');
        $this->sellingStatus = data_get($ticket, 'sellingStatus');
    }
}
