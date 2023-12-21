<?php
declare(strict_types=1);

namespace App\Responses\Infine;

class InitData
{
    public readonly string $no_order;
    public readonly string $infine_order;
    public readonly string $ds_uni;
    public readonly string $no_nozzle;
    public readonly string $nozzle_status;
    public readonly string $result_code;
    public readonly string $result_msg;
    public readonly string $dt_approval_temp;
    public readonly string $no_approval_temp;
    public readonly string $dt_approve;
    public readonly string $no_approve;
    public readonly string $no_deal;
    public readonly string $at_price;
    public readonly string $no_coupon;
    public readonly string $no_coupon_approve;
    public readonly string $at_coupon;
    public readonly string $no_bonuscard;
    public readonly string $no_bonuscard_approve;
    public readonly string $at_bonuscard;
    public readonly string $gas_kind;
    public readonly string $no_billkey;
    public readonly string $no_card;

    public function __construct(array $data)
    {
        $this->no_order = data_get($data, 'no_order');
        $this->infine_order = data_get($data, 'infine_order');
        $this->ds_uni = data_get($data, 'ds_uni');
        $this->no_nozzle = data_get($data, 'no_nozzle');
        $this->nozzle_status = data_get($data, 'nozzle_status');
        $this->result_code = data_get($data, 'result_code');
        $this->result_msg = data_get($data, 'result_msg');
        $this->dt_approval_temp = data_get($data, 'dt_approval_temp');
        $this->no_approval_temp = data_get($data, 'no_approval_temp');
        $this->dt_approve = data_get($data, 'dt_approve');
        $this->no_approve = data_get($data, 'no_approve');
        $this->no_deal = data_get($data, 'no_deal');
        $this->at_price = data_get($data, 'at_price');
        $this->no_coupon = data_get($data, 'no_coupon');
        $this->no_coupon_approve = data_get($data, 'no_coupon_approve');
        $this->at_coupon = data_get($data, 'at_coupon');
        $this->no_bonuscard = data_get($data, 'no_bonuscard');
        $this->no_bonuscard_approve = data_get($data, 'no_bonuscard_approve');
        $this->at_bonuscard = data_get($data, 'at_bonuscard');
        $this->no_billkey = data_get($data, 'no_billkey');
        $this->no_card = data_get($data, 'no_card');
    }
}
