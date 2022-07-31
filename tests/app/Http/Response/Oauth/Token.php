<?php

/**
 *
 */

declare(strict_types=1);

namespace Tests\app\Http\Response\Oauth;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Token
{
    #[OA\Property(description: '토큰 타입', example: 'Bearer')]
    public string $token_type;

    #[OA\Property(description: '토큰 인증 만료 시간', example: 1296000)]
    public int $expires_in;

    #[OA\Property(description: '인증 토큰')]
    public string $access_token;

    #[OA\Property(description: '리프레시 토큰')]
    public string $refresh_token;
}
