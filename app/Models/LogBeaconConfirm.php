<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class LogBeaconConfirm
 *
 * @property int $no
 * @property int $no_device
 * @property string|null $ds_sn
 * @property int $no_user
 * @property string|null $cd_phone_os
 * @property string|null $n1_b
 * @property string|null $n2_b
 * @property string|null $n3_b
 * @property string|null $n4_b
 * @property string|null $n5_b
 * @property string|null $n6_b
 * @property string|null $n7_b
 * @property string|null $otp_b
 * @property string|null $n1_a
 * @property string|null $n2_a
 * @property string|null $n3_a
 * @property string|null $n4_a
 * @property string|null $n5_a
 * @property string|null $n6_a
 * @property string|null $n7_a
 * @property string|null $n1_s
 * @property string|null $n2_s
 * @property string|null $n3_s
 * @property string|null $n4_s
 * @property string|null $n5_s
 * @property string|null $n6_s
 * @property string|null $n7_s
 * @property string|null $otp_s
 * @property string|null $error_command
 * @property string|null $server_error_command
 * @property string|null $error_param
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class LogBeaconConfirm extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_device' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_device',
        'ds_sn',
        'no_user',
        'cd_phone_os',
        'n1_b',
        'n2_b',
        'n3_b',
        'n4_b',
        'n5_b',
        'n6_b',
        'n7_b',
        'otp_b',
        'n1_a',
        'n2_a',
        'n3_a',
        'n4_a',
        'n5_a',
        'n6_a',
        'n7_a',
        'n1_s',
        'n2_s',
        'n3_s',
        'n4_s',
        'n5_s',
        'n6_s',
        'n7_s',
        'otp_s',
        'error_command',
        'server_error_command',
        'error_param',
        'dt_reg'
    ];
}
