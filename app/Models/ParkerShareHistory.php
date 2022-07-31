<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ParkerShareHistory
 *
 * @property int $no
 * @property int|null $no_device
 * @property string|null $yn_share
 * @property string|null $yn_mon_status
 * @property string|null $yn_tue_status
 * @property string|null $yn_wed_status
 * @property string|null $yn_thu_status
 * @property string|null $yn_fri_status
 * @property string|null $yn_sat_status
 * @property string|null $yn_sun_status
 * @property Carbon|null $tm_share_start
 * @property Carbon|null $tm_share_end
 * @property string|null $yn_end_nextday
 * @property int|null $at_basic_fee
 * @property int|null $at_over_fee
 * @property string|null $ds_share_name
 * @property string|null $ds_sido
 * @property string|null $ds_gugun
 * @property float|null $at_lat
 * @property float|null $at_lng
 * @property string|null $yn_auto_approval
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ParkerShareHistory extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_device' => 'int',
        'at_basic_fee' => 'int',
        'at_over_fee' => 'int',
        'at_lat' => 'float',
        'at_lng' => 'float'
    ];

    protected $dates = [
        'tm_share_start',
        'tm_share_end',
        'dt_reg'
    ];

    protected $fillable = [
        'no_device',
        'yn_share',
        'yn_mon_status',
        'yn_tue_status',
        'yn_wed_status',
        'yn_thu_status',
        'yn_fri_status',
        'yn_sat_status',
        'yn_sun_status',
        'tm_share_start',
        'tm_share_end',
        'yn_end_nextday',
        'at_basic_fee',
        'at_over_fee',
        'ds_share_name',
        'ds_sido',
        'ds_gugun',
        'at_lat',
        'at_lng',
        'yn_auto_approval',
        'dt_reg'
    ];
}
