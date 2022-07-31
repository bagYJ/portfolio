<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderListValet
 *
 * @property int $no
 * @property int|null $no_shop
 * @property int|null $nt_valet_num
 * @property int|null $nt_position
 * @property Carbon|null $dt_car_in
 * @property string|null $id_admin_in
 * @property Carbon|null $dt_car_out_request
 * @property Carbon|null $dt_car_out_response
 * @property Carbon|null $dt_car_out
 * @property string|null $id_admin_out
 * @property int|null $seq
 * @property int|null $no_maker
 * @property string|null $ds_car_info
 * @property string|null $cd_valet_order_status
 * @property float|null $at_price
 * @property string|null $cd_payment_valet
 * @property string|null $no_order
 * @property string|null $yn_on_site_pay
 * @property int|null $no_partner
 * @property float|null $at_commission_rate
 *
 * @package App\Models
 */
class OrderListValet extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $casts = [
        'no_shop' => 'int',
        'nt_valet_num' => 'int',
        'nt_position' => 'int',
        'seq' => 'int',
        'no_maker' => 'int',
        'at_price' => 'float',
        'no_partner' => 'int',
        'at_commission_rate' => 'float'
    ];

    protected $dates = [
        'dt_car_in',
        'dt_car_out_request',
        'dt_car_out_response',
        'dt_car_out'
    ];

    protected $fillable = [
        'no_shop',
        'nt_valet_num',
        'nt_position',
        'dt_car_in',
        'id_admin_in',
        'dt_car_out_request',
        'dt_car_out_response',
        'dt_car_out',
        'id_admin_out',
        'seq',
        'no_maker',
        'ds_car_info',
        'cd_valet_order_status',
        'at_price',
        'cd_payment_valet',
        'no_order',
        'yn_on_site_pay',
        'no_partner',
        'at_commission_rate'
    ];
}
