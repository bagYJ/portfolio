<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class OrderPayment
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
 * @property string|null $cd_reject_reason
 * @property string|null $ds_server_reg
 * @property Carbon|null $dt_reg
 * @property string|null $ds_dev_param
 * @property string|null $id_admin
 * @property float|null $at_pg_commission_rate
 * @property string|null $cd_commission_type
 * @property float|null $at_commission_amount
 * @property float|null $at_commission_rate
 * @property float|null $at_sales_commission_rate
 * @property string|null $tid
 * @property int|null $product_num
 * @property string|null $cancel_pwd
 * @property string|null $cancel_id
 *
 * @package App\Models
 */
class OrderPayment extends Model
{
    use Compoships;

    protected $primaryKey = 'no_payment';
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_payment' => 'int',
        'no_partner' => 'int',
        'no_shop' => 'int',
        'no_user' => 'int',
        'no_card' => 'int',
        'no_card_user' => 'int',
        'at_price' => 'float',
        'at_price_pg' => 'float',
        'at_pg_commission_rate' => 'float',
        'at_commission_amount' => 'float',
        'at_commission_rate' => 'float',
        'at_sales_commission_rate' => 'float',
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
        'no_payment',
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
        'cd_reject_reason',
        'ds_server_reg',
        'ds_dev_param',
        'id_admin',
        'at_pg_commission_rate',
        'cd_commission_type',
        'at_commission_amount',
        'at_commission_rate',
        'at_sales_commission_rate',
        'tid',
        'product_num',
        'cancel_pwd',
        'cancel_id'
    ];

    public function partner(): HasOne
    {
        return $this->hasOne(Partner::class, 'no_partner', 'no_partner');
    }

    public function memberCard(): HasOne
    {
        return $this->hasOne(MemberCard::class, ['no_card', 'cd_pg'], ['no_card', 'cd_pg']);
    }
}
