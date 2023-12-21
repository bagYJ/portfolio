<?php
declare(strict_types=1);

namespace App\Responses\Cu;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.cu')]
class Response
{
    #[OA\Property(description: '오윈에서 부여한 연동업체ID')]
    public readonly string $partner_code;
    #[OA\Property(description: '매장코드')]
    public readonly string $shop_code;
    #[OA\Property(description: '결과코드')]
    public readonly int|string $result_code;
    #[OA\Property(description: '결과메세지')]
    public readonly string $result_msg;

    public function __construct(array $response)
    {
        $this->partner_code = data_get($response, 'partner_code');
        $this->shop_code = data_get($response, 'shop_code');
        $this->result_code = data_get($response, 'result_code', getenv('RETURN_TRUE'));
        $this->result_msg = data_get($response, 'result_msg', '성공');
    }
}
