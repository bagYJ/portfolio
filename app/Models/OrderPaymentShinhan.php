<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderPaymentShinhan
 *
 * @property string $no_order
 * @property int|null $no_user
 * @property string|null $cd_pg
 * @property string|null $cd_payment
 * @property string|null $cd_payment_kind
 * @property string|null $cd_payment_status
 * @property int|null $no_card
 * @property string|null $cd_card_corp
 * @property int|null $no_card_user
 * @property float|null $at_price
 * @property float|null $at_price_pg
 * @property Carbon|null $dt_req
 * @property Carbon|null $dt_res
 * @property string|null $cd_pg_result
 * @property string|null $ds_res_code
 * @property string|null $ds_res_msg
 * @property string|null $ds_res_order_no
 * @property string|null $ds_req_param
 * @property string|null $ds_res_param
 * @property Carbon|null $dt_req_refund
 * @property Carbon|null $dt_res_refund
 * @property string|null $ds_res_code_refund
 * @property string|null $ds_req_refund
 * @property string|null $ds_res_refund
 * @property string|null $ds_server_reg
 * @property Carbon|null $dt_reg
 * @property string|null $id_admin
 *
 * @package App\Models
 */
class OrderPaymentShinhan extends Model
{
    protected $primaryKey = 'no_order';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_card' => 'int',
        'no_card_user' => 'int',
        'at_price' => 'float',
        'at_price_pg' => 'float'
    ];

    protected $dates = [
        'dt_req',
        'dt_res',
        'dt_req_refund',
        'dt_res_refund',
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'cd_pg',
        'cd_payment',
        'cd_payment_kind',
        'cd_payment_status',
        'no_card',
        'cd_card_corp',
        'no_card_user',
        'at_price',
        'at_price_pg',
        'dt_req',
        'dt_res',
        'cd_pg_result',
        'ds_res_code',
        'ds_res_msg',
        'ds_res_order_no',
        'ds_req_param',
        'ds_res_param',
        'dt_req_refund',
        'dt_res_refund',
        'ds_res_code_refund',
        'ds_req_refund',
        'ds_res_refund',
        'ds_server_reg',
        'dt_reg',
        'id_admin'
    ];
}
