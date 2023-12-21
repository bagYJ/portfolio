<?php
declare(strict_types=1);

namespace App\Requests\Ark;

use OpenApi\Attributes as OA;
use Psr\Container\{ContainerExceptionInterface, NotFoundExceptionInterface};

#[OA\Schema(schema: 'request.ark', description: '')]
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
