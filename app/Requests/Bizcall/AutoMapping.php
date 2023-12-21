<?php
declare(strict_types=1);

namespace App\Requests\Bizcall;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.bizcall.AutoMapping')]
class AutoMapping
{
    #[OA\Property(description: '사용자 번호')]
    public readonly string $realNumber;
    #[OA\Property(description: '업종')]
    public readonly ?string $cdBizKind;
    #[OA\Property(description: '주문번호')]
    public readonly ?string $noOrder;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->realNumber = data_get($valid, 'real_number');
        $this->cdBizKind = data_get($valid, 'cd_biz_kind');
        $this->noOrder = data_get($valid, 'no_order');
    }
}
