<?php
declare(strict_types=1);

namespace App\Responses\AutoWash;

class Coupon
{
    public readonly int $no;
    public readonly string $ds_cpn_no_internal;
    public readonly string $ds_cpn_no;
    public readonly int $no_user;
    public readonly int $no_partner;
    public readonly string $use_coupon_yn;
    public readonly string $ds_cpn_nm;
    public readonly string $use_disc_type;
    public readonly float $at_disct_money;
    public readonly float $at_limit_money;
    public readonly int $cd_payment_card;
    public readonly int $at_condi_liter;
    public readonly string $cd_mcp_status;
    public readonly string $cd_cpe_status;
    public readonly string $dt_use_start;
    public readonly string $dt_use_end;
    public readonly int $no_event;

    public function __construct(array $coupon)
    {
        $this->no = data_get($coupon, 'no');
        $this->ds_cpn_no_internal = data_get($coupon, 'ds_cpn_no_internal');
        $this->ds_cpn_no = data_get($coupon, 'ds_cpn_no');
        $this->no_user = data_get($coupon, 'no_user');
        $this->no_partner = data_get($coupon, 'no_partner');
        $this->use_coupon_yn = data_get($coupon, 'use_coupon_yn');
        $this->ds_cpn_nm = data_get($coupon, 'ds_cpn_nm');
        $this->use_disc_type = data_get($coupon, 'use_disc_type');
        $this->at_disct_money = data_get($coupon, 'at_disct_money');
        $this->at_limit_money = data_get($coupon, 'at_limit_money');
        $this->cd_payment_card = data_get($coupon, 'cd_payment_card');
        $this->at_condi_liter = data_get($coupon, 'at_condi_liter');
        $this->cd_mcp_status = data_get($coupon, 'cd_mcp_status');
        $this->cd_cpe_status = data_get($coupon, 'cd_cpe_status');
        $this->dt_use_start = data_get($coupon, 'dt_use_start');
        $this->dt_use_end = data_get($coupon, 'dt_use_end');
        $this->no_event = data_get($coupon, 'no_event');
    }
}
