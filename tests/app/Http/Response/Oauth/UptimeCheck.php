<?php

namespace Tests\app\Http\Response\Oauth;

use OpenApi\Attributes as OA;

#[OA\Schema]
class UptimeCheck
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;

    #[OA\Property(description: '결과 메시지')]
    public string $current_msg;

    #[OA\Property(description: '결과 코드')]
    public string $cd_uptime_msg;
}
