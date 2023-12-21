<?php
declare(strict_types=1);

namespace App\Requests;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.Page', description: '')]
class Page
{
    #[OA\Property(description: '페이지당 항목 개수')]
    public readonly ?int $size;
    #[OA\Property(description: '페이지 offset')]
    public readonly ?int $offset;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->size = data_get($valid, 'size', 0);
        $this->offset = data_get($valid, 'offset', 0);
    }
}
