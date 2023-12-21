<?php
declare(strict_types=1);

namespace App\Requests\Infine;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.infine.Cancel', description: '')]
class Cancel
{
    #[OA\Property(description: '주문번호')]
    public readonly string $noOrder;
    #[OA\Property(description: '인파인 주문번호')]
    public readonly string $infineOrder;
    #[OA\Property(description: '1: 선승인 취소, 2: 본승인 취소')]
    public readonly string $cancelType;
    #[OA\Property(description: '취소할 승인 번호')]
    public readonly string $noApproval;

    /**
     * @param Request $request
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->noOrder = data_get($valid, 'no_order');
        $this->infineOrder = data_get($valid, 'infine_order');
        $this->cancelType = data_get($valid, 'cancel_type');
        $this->noApproval = data_get($valid, 'no_approval');
    }
}
