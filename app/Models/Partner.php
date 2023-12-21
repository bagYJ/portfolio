<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Partner
 *
 * @property int $no
 * @property int $no_partner
 * @property string|null $nm_partner
 * @property string|null $cd_biz_kind
 * @property string|null $cd_biz_kind_detail
 * @property string|null $cd_sale_kind
 * @property string|null $ds_bi
 * @property string|null $ds_pin
 * @property string|null $ds_info_bg
 * @property string|null $yn_status
 * @property Carbon|null $dt_reg
 * @property string|null $cd_service
 * @property string|null $ds_address
 * @property string|null $ds_partner_admin
 * @property string|null $ds_tel
 * @property string|null $ds_email
 * @property string|null $ds_image_bg
 * @property string|null $ds_image_ci
 * @property Carbon|null $dt_contract_start
 * @property Carbon|null $dt_contract_end
 * @property string|null $yn_auto_extend
 * @property string|null $ds_contract_url
 * @property string|null $cd_commission_type
 * @property float|null $at_commission_amount
 * @property float|null $at_commission_rate
 * @property float|null $at_comm_rate_general
 * @property int|null $ct_commission_sales
 * @property Carbon|null $dt_upt
 * @property int|null $no_company
 * @property int|null $at_make_ready_time
 * @property string|null $ds_menu_origin
 * @property int|null $no_sales_agency
 * @property float|null $at_sales_commission_rate
 * @property string|null $cd_pg
 * @property string|null $ds_pg_id
 * @property float|null $at_pg_commission_rate
 * @property string|null $cd_bank
 * @property string|null $ds_bank_acct
 * @property string|null $nm_acct_name
 * @property string|null $cd_contract_status
 * @property string|null $ds_biz_num
 * @property string|null $cd_calculate_main
 * @property string|null $ds_calc_email
 * @property Carbon|null $dt_calc_email_upt
 * @property string|null $id_admin
 * @property string|null $ds_external_company
 * @property string|null $tags
 * @property string|null $cd_spc_brand
 * @property string|null $id_reg
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 *
 * @package App\Models
 */
class Partner extends Model
{
    public $incrementing = false;
    public $timestamps = true;
    protected $primaryKey = 'no_partner';
    protected $casts = [];

    protected $fillable = [];
}
