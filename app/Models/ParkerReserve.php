<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ParkerReserve
 *
 * @property int $no_rsv
 * @property int|null $no_user
 * @property int|null $no_device
 * @property string|null $cd_rsv_status
 * @property Carbon|null $dt_rsv_start
 * @property Carbon|null $dt_rsv_end
 * @property float|null $at_rsv_price
 * @property float|null $at_fee_rate
 * @property float|null $at_rsv_fee
 * @property float|null $at_cancel_rate
 * @property float|null $at_cancel_price
 * @property float|null $at_cancel_fee
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $cd_rsv_alarm
 * @property string|null $ds_adver
 * @property string|null $no_order
 * @property Carbon|null $dt_use_start
 * @property Carbon|null $dt_use_end
 * @property string|null $no_order_over
 * @property float|null $at_over_price
 * @property float|null $at_over_fee
 *
 * @package App\Models
 */
class ParkerReserve extends Model
{
    protected $primaryKey = 'no_rsv';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_rsv' => 'int',
        'no_user' => 'int',
        'no_device' => 'int',
        'at_rsv_price' => 'float',
        'at_fee_rate' => 'float',
        'at_rsv_fee' => 'float',
        'at_cancel_rate' => 'float',
        'at_cancel_price' => 'float',
        'at_cancel_fee' => 'float',
        'at_over_price' => 'float',
        'at_over_fee' => 'float'
    ];

    protected $dates = [
        'dt_rsv_start',
        'dt_rsv_end',
        'dt_reg',
        'dt_upt',
        'dt_use_start',
        'dt_use_end'
    ];

    protected $fillable = [
        'no_user',
        'no_device',
        'cd_rsv_status',
        'dt_rsv_start',
        'dt_rsv_end',
        'at_rsv_price',
        'at_fee_rate',
        'at_rsv_fee',
        'at_cancel_rate',
        'at_cancel_price',
        'at_cancel_fee',
        'dt_reg',
        'dt_upt',
        'cd_rsv_alarm',
        'ds_adver',
        'no_order',
        'dt_use_start',
        'dt_use_end',
        'no_order_over',
        'at_over_price',
        'at_over_fee'
    ];
}
