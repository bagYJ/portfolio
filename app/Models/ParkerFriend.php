<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ParkerFriend
 *
 * @property int $no_device
 * @property int $no_user
 * @property string|null $ds_adver
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ParkerFriend extends Model
{
    public $incrementing = false;
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
        'ds_adver',
        'dt_reg'
    ];
}
