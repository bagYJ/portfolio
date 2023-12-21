<?php
declare(strict_types=1);

namespace App\Responses\Infine;

class Approval extends Response
{
    public readonly ?ApprovalData $data;
    public function __construct(array $response)
    {
        parent::__construct($response);
        $this->data = new ApprovalData(data_get($response, 'data'));
    }
}
