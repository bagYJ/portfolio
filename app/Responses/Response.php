<?php
declare(strict_types=1);

namespace App\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Response
{
    #[OA\Property(description: '결과코드')]
    public readonly string $code;

    public function __construct()
    {
        $this->code = getenv('RETURN_TRUE');
    }
}
