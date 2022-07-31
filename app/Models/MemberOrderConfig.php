<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberOrderConfig
 *
 * @property int $no_user
 * @property int $no_partner
 * @property float $at_use_cash
 * @property float $at_use_point
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class MemberOrderConfig extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_partner' => 'int',
        'at_use_cash' => 'float',
        'at_use_point' => 'float'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'at_use_cash',
        'at_use_point',
        'dt_reg',
        'dt_upt'
    ];
}
