<?php
declare(strict_types=1);

namespace App\Responses\AutoWash;

class Partner
{
    public readonly int $no;
    public readonly int $no_partner;
    public readonly string $nm_partner;
    public readonly string $cd_biz_kind;
    public readonly string $cd_biz_kind_detail;
    public readonly string $cd_sale_kind;
    public readonly string $ds_bi;
    public readonly string $ds_pin;
    public readonly string $ds_info_bg;
    public readonly string $yn_status;
    public readonly string $dt_reg;
    public readonly string $cd_service;
    public readonly string $ds_address;
    public readonly string $ds_partner_admin;
    public readonly string $ds_tel;
    public readonly string $ds_email;
    public readonly string $ds_image_bg;
    public readonly string $ds_image_ci;
    public readonly string $dt_contract_start;
    public readonly string $dt_contract_end;
    public readonly string $yn_auto_extend;
    public readonly string $ds_contract_url;
    public readonly string $cd_commission_type;
    public readonly float $at_commission_amount;
    public readonly float $at_commission_rate;
    public readonly float $at_comm_rate_general;
    public readonly int $ct_commission_sales;
    public readonly string $dt_upt;
    public readonly int $no_company;
    public readonly string $ds_menu_origin;
    public readonly int $no_sales_agency;
    public readonly float $at_sales_commission_rate;
    public readonly string $cd_pg;
    public readonly string $ds_pg_id;
    public readonly float $at_pg_commission_rate;
    public readonly string $cd_bank;
    public readonly string $ds_bank_acct;
    public readonly string $nm_acct_name;
    public readonly string $cd_contract_status;
    public readonly string $ds_biz_num;
    public readonly string $cd_calculate_main;
    public readonly string $ds_calc_email;
    public readonly string $dt_calc_email_upt;
    public readonly string $id_admin;
    public readonly string $ds_external_company;
    public function __construct(array $partner)
    {
        $this->no = data_get($partner, 'no');
        $this->no_partner = data_get($partner, 'no_partner');
        $this->nm_partner = data_get($partner, 'nm_partner');
        $this->cd_biz_kind = data_get($partner, 'cd_biz_kind');
        $this->cd_biz_kind_detail = data_get($partner, 'cd_biz_kind_detail');
        $this->cd_sale_kind = data_get($partner, 'cd_sale_kind');
        $this->ds_bi = data_get($partner, 'ds_bi');
        $this->ds_pin = data_get($partner, 'ds_pin');
        $this->ds_info_bg = data_get($partner, 'ds_info_bg');
        $this->yn_status = data_get($partner, 'yn_status');
        $this->dt_reg = data_get($partner, 'dt_reg');
        $this->cd_service = data_get($partner, 'cd_service');
        $this->ds_address = data_get($partner, 'ds_address');
        $this->ds_partner_admin = data_get($partner, 'ds_partner_admin');
        $this->ds_tel = data_get($partner, 'ds_tel');
        $this->ds_email = data_get($partner, 'ds_email');
        $this->ds_image_bg = data_get($partner, 'ds_image_bg');
        $this->ds_image_ci = data_get($partner, 'ds_image_ci');
        $this->dt_contract_start = data_get($partner, 'dt_contract_start');
        $this->dt_contract_end = data_get($partner, 'dt_contract_end');
        $this->yn_auto_extend = data_get($partner, 'yn_auto_extend');
        $this->ds_contract_url = data_get($partner, 'ds_contract_url');
        $this->cd_commission_type = data_get($partner, 'cd_commission_type');
        $this->at_commission_amount = data_get($partner, 'at_commission_amount');
        $this->at_commission_rate = data_get($partner, 'at_commission_rate');
        $this->at_comm_rate_general = data_get($partner, 'at_comm_rate_general');
        $this->ct_commission_sales = data_get($partner, 'ct_commission_sales');
        $this->dt_upt = data_get($partner, 'dt_upt');
        $this->no_company = data_get($partner, 'no_company');
        $this->ds_menu_origin = data_get($partner, 'ds_menu_origin');
        $this->no_sales_agency = data_get($partner, 'no_sales_agency');
        $this->at_sales_commission_rate = data_get($partner, 'at_sales_commission_rate');
        $this->cd_pg = data_get($partner, 'cd_pg');
        $this->ds_pg_id = data_get($partner, 'ds_pg_id');
        $this->at_pg_commission_rate = data_get($partner, 'at_pg_commission_rate');
        $this->cd_bank = data_get($partner, 'cd_bank');
        $this->ds_bank_acct = data_get($partner, 'ds_bank_acct');
        $this->nm_acct_name = data_get($partner, 'nm_acct_name');
        $this->cd_contract_status = data_get($partner, 'cd_contract_status');
        $this->ds_biz_num = data_get($partner, 'ds_biz_num');
        $this->cd_calculate_main = data_get($partner, 'cd_calculate_main');
        $this->ds_calc_email = data_get($partner, 'ds_calc_email');
        $this->dt_calc_email_upt = data_get($partner, 'dt_calc_email_upt');
        $this->id_admin = data_get($partner, 'id_admin');
        $this->ds_external_company = data_get($partner, 'ds_external_company');
    }
}
