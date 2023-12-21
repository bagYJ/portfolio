<?php
declare(strict_types=1);

namespace App\Responses\AutoParking;

use App\Utils\Code;
use OpenApi\Attributes as OA;
use Throwable;

#[OA\Schema(schema: 'response.autoparking.Failed')]
class Failed
{
    #[OA\Property(description: '결과 ')]
    public readonly string $result;
    #[OA\Property(description: '인터페이스 코드')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '결과 메세지')]
    public readonly string $resultMessage;
    #[OA\Property(description: '결과 코드')]
    public readonly string|int $code;

    public function __construct(Throwable $t)
    {
        $this->result = 'failure';
        $this->interfaceCode = $t->getMessage();
        $this->code = $t->getCode();
//        $this->resultMessage = Code::message(sprintf('auto-parking.%s', $this->code));
        $this->resultMessage = $t->getCode() . $t->getMessage();
    }
}
