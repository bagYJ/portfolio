<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ParkerShare
 *
 * @property int $no_device
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
 * @property string|null $yn_use
 * @property string|null $cd_parker_status
 *
 * @package App\Models
 */
class ParkerShare extends Model
{
    protected $primaryKey = 'no_device';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no_device' => 'int',
        'at_basic_fee' => 'int',
        'at_over_fee' => 'int',
        'at_lat' => 'float',
        'at_lng' => 'float'
    ];

    protected $dates = [
        'tm_share_start',
        'tm_share_end'
    ];

    protected $fillable = [
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
        'yn_use',
        'cd_parker_status'
    ];
}
