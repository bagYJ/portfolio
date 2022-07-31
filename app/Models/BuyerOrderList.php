<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class BuyerOrderList
 *
 * @property string $no_order
 * @property string $nm_order
 * @property string $id_buyer
 * @property int $nt_buy_cnt
 * @property string|null $cd_pg
 * @property string|null $ds_pg_id
 * @property float $at_price
 * @property string|null $cd_payment_status
 * @property Carbon|null $dt_payment_status
 * @property Carbon|null $dt_reg
 * @property string|null $ds_res_order_no
 * @property Carbon|null $dt_res
 * @property string|null $ds_res_code
 * @property string|null $ds_res_msg
 * @property string|null $ds_req_param
 * @property string|null $ds_res_param
 * @property Carbon|null $dt_res_refund
 * @property string|null $ds_res_code_refund
 * @property string|null $ds_req_refund
 * @property string|null $ds_res_refund
 * @property string|null $ds_server_reg
 *
 * @package App\Models
 */
class BuyerOrderList extends Model
{
    protected $primaryKey = 'no_order';
    public $incrementing = false;
    public $timestamps = true;


    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'nt_buy_cnt' => 'int',
        'at_price' => 'float'
    ];

    protected $dates = [
        'dt_payment_status',
        'dt_reg',
        'dt_res',
        'dt_res_refund'
    ];

    protected $fillable = [
        'nm_order',
        'id_buyer',
        'nt_buy_cnt',
        'cd_pg',
        'ds_pg_id',
        'at_price',
        'cd_payment_status',
        'dt_payment_status',
        'dt_reg',
        'ds_res_order_no',
        'dt_res',
        'ds_res_code',
        'ds_res_msg',
        'ds_req_param',
        'ds_res_param',
        'dt_res_refund',
        'ds_res_code_refund',
        'ds_req_refund',
        'ds_res_refund',
        'ds_server_reg'
    ];
}
