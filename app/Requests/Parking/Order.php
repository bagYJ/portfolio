<?php
declare(strict_types=1);

namespace App\Requests\Parking;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.parking.Order', description: '')]
class Order
{
    #[OA\Property(description: '할인권 고유번호')]
    public readonly int $ticketUid;
    #[OA\Property(description: '차량번호(공백없이)')]
    public readonly string $carPlate;
    #[OA\Property(description: '유저식별자')]
    public readonly string $userCode;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->ticketUid = (int)data_get($valid, 'ticketUid');
        $this->carPlate = data_get($valid, 'carPlate');
        $this->userCode = (string)data_get($valid, 'userCode');
    }
}
