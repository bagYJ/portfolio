<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PaymentRequest
 *
 * @property int $no
 * @property int|null $no_payment
 * @property int|null $no_user
 * @property int|null $no_product
 * @property int|null $no_device
 * @property string|null $cd_payment
 * @property float|null $at_price
 * @property float|null $at_price_event
 * @property string|null $ds_otp
 * @property string|null $ds_otp_server
 * @property string|null $ds_status
 * @property string|null $ds_errcode
 * @property string|null $ds_otp_beacon
 * @property Carbon|null $dt_req
 * @property Carbon|null $dt_proc
 *
 * @package App\Models
 */
class PaymentRequest extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_payment' => 'int',
        'no_user' => 'int',
        'no_product' => 'int',
        'no_device' => 'int',
        'at_price' => 'float',
        'at_price_event' => 'float'
    ];

    protected $dates = [
        'dt_req',
        'dt_proc'
    ];

    protected $fillable = [
        'no_payment',
        'no_user',
        'no_product',
        'no_device',
        'cd_payment',
        'at_price',
        'at_price_event',
        'ds_otp',
        'ds_otp_server',
        'ds_status',
        'ds_errcode',
        'ds_otp_beacon',
        'dt_req',
        'dt_proc'
    ];
}
