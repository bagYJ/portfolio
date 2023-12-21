<?php
declare(strict_types=1);

namespace App\Responses\Bizcall;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.bizcall.CloseMapping')]
class CloseMapping
{
    #[OA\Property(description: '결과 코드')]
    public readonly int $rt;
    #[OA\Property(description: '실패사유')]
    public readonly string $rs;

    public function __construct(array $response)
    {
        $this->rt = (int)data_get($response, 'rt');
        $this->rs = data_get($response, 'rs');
    }
}
