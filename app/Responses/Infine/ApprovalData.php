<?php
declare(strict_types=1);

namespace App\Responses\Infine;

class ApprovalData
{
    public readonly string $noOrder;
    public readonly string $infineOrder;
    public readonly string $resultCode;
    public readonly ?string $resultMsg;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->noOrder = data_get($data, 'no_order');
        $this->infineOrder = data_get($data, 'infine_order');
        $this->resultCode = data_get($data, 'result_code');
        $this->resultMsg = data_get($data, 'result_msg');
    }
}
