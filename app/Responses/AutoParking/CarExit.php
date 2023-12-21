<?php
declare(strict_types=1);

namespace App\Responses\AutoParking;

use App\Utils\Code;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.autoparking.CarExit')]
class CarExit
{
    #[OA\Property(description: '인터페이스 코드', example: 'IF_0005')]
    public readonly string $interfaceCode;
    #[OA\Property(description: '결과 메세지')]
    public readonly ?string $resultMessage;
    #[OA\Property(description: '결과 코드')]
    public readonly string $resultCode;

    public function __construct(array $response)
    {
        $this->interfaceCode = 'IF_0005';
        $this->resultCode =  (string)match (data_get($response, 'result')) {
            true => getenv('RETURN_TRUE'),
            default => data_get($response, 'code', '9999')
        };
        $this->resultMessage = Code::message(sprintf('auto-parking.%s', $this->resultCode));
    }
}
