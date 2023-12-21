<?php
declare(strict_types=1);

namespace App\Responses\Bizcall;

use App\Responses\Response;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.bizcall.AutoMapping')]
class AutoMapping extends Response
{
    #[OA\Property(description: '결과 코드')]
    public readonly int $rt;
    #[OA\Property(description: '가상번호')]
    public readonly string $vn;
    #[OA\Property(description: '실패사유')]
    public readonly string $rs;

    public function __construct(array $response)
    {
        parent::__construct();
        $this->rt = (int)data_get($response, 'rt');
        $this->vn = data_get($response, 'vn');
        $this->rs = data_get($response, 'rs');
    }
}
