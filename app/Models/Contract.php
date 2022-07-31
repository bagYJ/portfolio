<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Contract
 *
 * @property int $no
 * @property string|null $nm_company
 * @property string|null $ds_comp_number
 * @property string|null $ds_comp_admin
 * @property string|null $ds_tel
 * @property string|null $ds_tel2
 * @property string|null $ds_email
 * @property string|null $ds_passwd
 * @property string|null $cd_comp_kind
 * @property string|null $yn_agree
 * @property string|null $yn_submit
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $cd_status
 * @property string|null $cd_commission_type
 * @property float|null $at_commission_value
 * @property string|null $ds_commi_product
 * @property string|null $ds_contract_url
 *
 * @package App\Models
 */
class Contract extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'at_commission_value' => 'float'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'nm_company',
        'ds_comp_number',
        'ds_comp_admin',
        'ds_tel',
        'ds_tel2',
        'ds_email',
        'ds_passwd',
        'cd_comp_kind',
        'yn_agree',
        'yn_submit',
        'dt_reg',
        'dt_upt',
        'cd_status',
        'cd_commission_type',
        'at_commission_value',
        'ds_commi_product',
        'ds_contract_url'
    ];
}
