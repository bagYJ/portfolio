<?php

/**
 *
 */

declare(strict_types=1);

namespace Tests\app\Http\Response\Oauth;

use OpenApi\Attributes as OA;

#[OA\Schema]
class GetRegistCode
{
    #[OA\Property]
    public bool $result;

    #[OA\Property(description: '인증코드번호', example: 938123)]
    public string $oauth_code;
}
