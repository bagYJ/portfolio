<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Parker
 *
 * @property int $no_device
 * @property int|null $no_user
 * @property string|null $ds_sn
 * @property string|null $ds_addr
 * @property Carbon|null $dt_pairing
 * @property string|null $ds_pwd
 * @property string|null $ds_img_url1
 * @property string|null $ds_img_url2
 * @property string|null $ds_img_url3
 * @property string|null $ds_img_url4
 * @property string|null $ds_detail
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $ds_macaddr
 * @property int|null $at_parker_status
 * @property int|null $at_ir_sensor
 * @property string|null $ds_remain_volt
 * @property string|null $ds_nation
 *
 * @package App\Models
 */
class Parker extends Model
{
    protected $primaryKey = 'no_device';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_device' => 'int',
        'no_user' => 'int',
        'at_parker_status' => 'int',
        'at_ir_sensor' => 'int'
    ];

    protected $dates = [
        'dt_pairing',
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no_user',
        'ds_sn',
        'ds_addr',
        'dt_pairing',
        'ds_pwd',
        'ds_img_url1',
        'ds_img_url2',
        'ds_img_url3',
        'ds_img_url4',
        'ds_detail',
        'dt_reg',
        'dt_upt',
        'ds_macaddr',
        'at_parker_status',
        'at_ir_sensor',
        'ds_remain_volt',
        'ds_nation'
    ];
}
