<?php
declare(strict_types=1);

namespace App\Responses\Parking;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.parking.Booking')]
class Booking
{
    #[OA\Property(description: '고유번호')]
    public readonly int $uid;
    #[OA\Property(description: '주차장 고유번호')]
    public readonly int $siteUid;
    #[OA\Property(description: '주차장명')]
    public readonly string $siteName;
    #[OA\Property(description: '할인권 고유번호')]
    public readonly int $ticketUid;
    #[OA\Property(description: '할인권명')]
    public readonly string $ticketTitle;
    #[OA\Property(description: '이용차량 차량번호')]
    public readonly string $carPlate;
    #[OA\Property(description: '상태:\n* WAIT - 사용전, 대기상태, 입차전\n* USED - 사용완료\n* CANCELED - 취소\n* EXPIRED - 만료')]
    public readonly string $status;
    #[OA\Property(description: '유저식별자')]
    public readonly string $userCode;
    #[OA\Property(description: '할인권이 적용된 일시 (WAIT > USED)')]
    public readonly ?string $usedAt;
    #[OA\Property(description: '할인권이 취소된 일시 (WAIT > CANCELED)')]
    public readonly ?string $canceledAt;
    #[OA\Property(description: '할인권 만료 예정 일시')]
    public readonly string $expiredAt;
    #[OA\Property(description: '할인권 구매일시')]
    public readonly string $createdAt;

    public function __construct(array $booking)
    {
        $this->uid = (int)data_get($booking, 'uid');
        $this->siteUid = (int)data_get($booking, 'siteUid');
        $this->siteName = data_get($booking, 'siteName');
        $this->ticketUid = (int)data_get($booking, 'ticketUid');
        $this->ticketTitle = data_get($booking, 'ticketTitle');
        $this->carPlate = data_get($booking, 'carPlate');
        $this->status = data_get($booking, 'status');
        $this->userCode = data_get($booking, 'userCode');
        $this->usedAt = data_get($booking, 'usedAt');
        $this->canceledAt = data_get($booking, 'canceledAt');
        $this->expiredAt = data_get($booking, 'expiredAt');
        $this->createdAt = data_get($booking, 'createdAt');
    }
}
