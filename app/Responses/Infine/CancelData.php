<?php
declare(strict_types=1);

namespace App\Responses\Infine;

class CancelData
{
    public readonly string $no_order;
    public readonly string $infine_order;
    public readonly string $cancel_type;
    public readonly string $no_approval;
    public readonly string $dt_approval_cancel;
    public readonly string $result_code;
    public readonly string $result_msg;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->no_order = data_get($data, 'no_order');
        $this->infine_order = data_get($data, 'infine_order');
        $this->cancel_type = data_get($data, 'cancel_type');
        $this->no_approval = data_get($data, 'no_approval');
        $this->dt_approval_cancel = data_get($data, 'dt_approval_cancel');
        $this->result_code = data_get($data, 'result_code');
        $this->result_msg = data_get($data, 'result_msg');
    }
}
