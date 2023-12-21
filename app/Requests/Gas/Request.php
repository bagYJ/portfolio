<?php
declare(strict_types=1);

namespace App\Requests\Gas;

use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.gas', description: '')]
class Request
{
    #[OA\Property(description: '본문')]
    public readonly string $body;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(\Illuminate\Http\Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->body = data_get($valid, 'body');
    }
}
