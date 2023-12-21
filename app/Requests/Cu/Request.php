<?php
declare(strict_types=1);

namespace App\Requests\Cu;

use App\Exceptions\ValidationHashException;
use App\Utils\Common;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'request.cu')]
class Request
{
    #[OA\Property(description: '오윈에서 부여한 연동업체ID')]
    public readonly string $partner_code;
    #[OA\Property(description: '요청 hash data')]
    public readonly string $sign;
    #[OA\Property(description: '전송일시')]
    public string $trans_dt;

    public function __construct()
    {
        $this->partner_code = getenv('CU_PARTNER_CODE');
        $this->trans_dt = date('YmdHis');
    }

    public function setSign(string $data): self
    {
        $this->sign = Common::getHash($data);

        return $this;
    }

    public function makeRequest(): \Illuminate\Http\Request
    {
        $opts = ['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: Application/json',
            'timeout' => getenv('CLIENT_TIMEOUT')
        ]];
        $context = stream_context_create($opts);
        $post = file_get_contents('php://input', true, $context);

        return (new \Illuminate\Http\Request())->merge(json_decode(base64_decode($post), true));
    }

    /**
     * @throws ValidationHashException
     */
    public function hashCheck(string $sign): void
    {
        if ($this->sign != $sign) {
            throw new ValidationHashException();
        }
    }

    public function json(): string
    {
        return json_encode($this);
    }
}
