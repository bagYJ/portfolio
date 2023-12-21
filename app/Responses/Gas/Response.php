<?php
declare(strict_types=1);

namespace App\Responses\Gas;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.gas')]
class Response extends \App\Responses\Response
{
    #[OA\Property(description: '소켓처리정보')]
    public readonly string $message;

    public function __construct(string $message)
    {
        parent::__construct();
        $this->message = $message;
    }
}
