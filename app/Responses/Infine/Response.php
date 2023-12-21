<?php
declare(strict_types=1);

namespace App\Responses\Infine;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.infine.Response', description: '')]
class Response
{
    #[OA\Property(description: '응답결과코드 (성공 : 0000, 실패 : 기타)')]
    public readonly string $code;
    #[OA\Property(description: '에러 정보')]
    public readonly string $message;
    public function __construct(array $response)
    {
        $this->code = data_get($response, 'code');
        $this->message = data_get($response, 'message');
    }
}
