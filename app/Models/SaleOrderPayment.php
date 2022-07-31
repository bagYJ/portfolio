<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class SaleOrderPayment
 *
 * @property int $no
 * @property string $no_order
 * @property int $no_payment
 * @property int|null $no_partner
 * @property int|null $no_shop
 * @property int $no_user
 * @property string|null $cd_pg
 * @property string|null $cd_payment
 * @property string|null $cd_payment_kind
 * @property string|null $cd_payment_status
 * @property int|null $no_card
 * @property string|null $cd_card_corp
 * @property string|null $no_card_user
 * @property float|null $at_price
 * @property float|null $at_price_pg
 * @property float|null $at_pg_commission_rate
 * @property string|null $cd_pg_result
 * @property Carbon|null $dt_req
 * @property Carbon|null $dt_res
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
 * @property string|null $cd_reject_reason
 * @property string|null $id_admin
 * @property string|null $tid
 * @property int|null $product_num
 * @property string|null $cancel_pwd
 * @property string|null $cancel_id
 * @property string|null $ds_server_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class SaleOrderPayment extends Model
{
    protected $primaryKey = 'no_payment';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_payment' => 'int',
        'no_partner' => 'int',
        'no_shop' => 'int',
        'no_user' => 'int',
        'no_card' => 'int',
        'at_price' => 'float',
        'at_price_pg' => 'float',
        'at_pg_commission_rate' => 'float',
        'product_num' => 'int'
    ];

    protected $dates = [
        'dt_req',
        'dt_res',
        'dt_req_refund',
        'dt_res_refund',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'no_order',
        'no_partner',
        'no_shop',
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
        'at_pg_commission_rate',
        'cd_pg_result',
        'dt_req',
        'dt_res',
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
        'cd_reject_reason',
        'id_admin',
        'tid',
        'product_num',
        'cancel_pwd',
        'cancel_id',
        'ds_server_reg',
        'dt_reg'
    ];
}
