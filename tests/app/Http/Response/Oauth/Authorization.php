<?php

/**
 *
 */

declare(strict_types=1);

namespace Tests\app\Http\Response\Oauth;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Authorization
{
    #[OA\Property]
    public bool $result;

    #[OA\Property(description: '인증토큰')]
    public string $access_token;
}
