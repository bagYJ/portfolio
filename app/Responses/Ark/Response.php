<?php
declare(strict_types=1);

namespace App\Responses\Ark;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.ark', description: '')]
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
