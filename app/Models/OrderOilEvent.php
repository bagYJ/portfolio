<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderOilEvent
 *
 * @property string $no_order
 * @property string $cd_order_event_type
 * @property string|null $ds_credit_card_no
 * @property string|null $ds_credit_auth_no
 * @property string|null $ds_trdate_credit
 * @property string|null $ds_bon_card_no
 * @property string|null $ds_bon_card_auth_no
 * @property string|null $ds_trdate_bon
 * @property string|null $ds_bon_savetype_no
 * @property string|null $ds_car_number
 * @property Carbon|null $dt_reg
 * @property string|null $ds_payment_type
 * @property string|null $yn_payment_result
 * @property string|null $yn_last_check
 * @property float|null $at_disct_pg
 * @property float|null $at_price_pg
 * @property float|null $oil_liter_pg
 * @property string|null $ds_unit_id
 * @property float|null $at_p_point_for_add
 * @property string|null $yn_pre_approve_fail
 * @property Carbon|null $dt_pre_approve_fail
 * @property string|null $ds_fail_msg
 *
 * @package App\Models
 */
class OrderOilEvent extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'at_disct_pg' => 'float',
        'at_price_pg' => 'float',
        'oil_liter_pg' => 'float',
        'at_p_point_for_add' => 'float'
    ];

    protected $dates = [
        'dt_reg',
        'dt_pre_approve_fail'
    ];

    protected $fillable = [
        'ds_credit_card_no',
        'ds_credit_auth_no',
        'ds_trdate_credit',
        'ds_bon_card_no',
        'ds_bon_card_auth_no',
        'ds_trdate_bon',
        'ds_bon_savetype_no',
        'ds_car_number',
        'dt_reg',
        'ds_payment_type',
        'yn_payment_result',
        'yn_last_check',
        'at_disct_pg',
        'at_price_pg',
        'oil_liter_pg',
        'ds_unit_id',
        'at_p_point_for_add',
        'yn_pre_approve_fail',
        'dt_pre_approve_fail',
        'ds_fail_msg'
    ];
}
