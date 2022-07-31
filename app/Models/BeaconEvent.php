<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class BeaconEvent
 *
 * @property string $ds_sn
 * @property int $no_event
 * @property Carbon|null $dt_reg
 * @property string $yn_coupon_published
 *
 * @package App\Models
 */
class BeaconEvent extends Model
{
    protected $primaryKey = 'ds_sn';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_event' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_event',
        'dt_reg',
        'yn_coupon_published'
    ];
}
