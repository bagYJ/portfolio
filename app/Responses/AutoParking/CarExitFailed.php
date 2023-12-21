<?php
declare(strict_types=1);

namespace App\Responses\AutoParking;

use OpenApi\Attributes as OA;
use Throwable;

#[OA\Schema(schema: 'response.autoparking.CarExitFailed')]
class CarExitFailed
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0005')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '결과 메세지')]
    public readonly ?string $resultMessage;
    #[OA\Property(description: '결과 코드')]
    public readonly string $resultCode;

    public function __construct(Throwable $t)
    {
        $this->interfaceCode = 'IF_0005';
        $this->resultCode = '9999';
        $this->resultMessage = $t->getMessage();
    }
}
