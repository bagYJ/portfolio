<?php
declare(strict_types=1);

namespace App\Responses\Infine;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.infine.ApprovalResultData', description: '')]
class ApprovalResultData
{
    #[OA\Property(description: '주문번호')]
    public readonly string $no_order;
    #[OA\Property(description: '인파인 주문번호')]
    public readonly string $infine_order;

    public function __construct(array $data)
    {
        $this->no_order = data_get($data, 'no_order');
        $this->infine_order = data_get($data, 'infine_order');
    }
}
