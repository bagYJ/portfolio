<?php
declare(strict_types=1);

namespace App\Responses\Bizcall;

use App\Responses\Response;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.bizcall.GetVns')]
class GetVns extends Response
{
    #[OA\Property(description: '결과 코드')]
    public readonly int $rt;
    #[OA\Property(description: '실패사유')]
    public readonly string $rs;
    #[OA\Property(description: '가상번호 리스트')]
    public readonly Collection $rec;

    public function __construct(array $response)
    {
        parent::__construct();
        $this->rt = (int)data_get($response, 'rt');
        $this->rs = data_get($response, 'rs');
        $this->rec = collect(data_get($response, 'rec'))->map(function (array $rec) {
            return (new Rec($rec));
        });
    }
}
