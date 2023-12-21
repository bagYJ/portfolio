<?php
declare(strict_types=1);

namespace App\Responses\Infine;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.infine.ApprovalResult', description: '')]
class ApprovalResult extends Response
{
    #[OA\Property(description: '데이터')]
    public readonly ?ApprovalResultData $data;

    public function __construct(array $response)
    {
        parent::__construct($response);
        $this->data = new ApprovalResultData(data_get($response, 'data'));
    }
}
