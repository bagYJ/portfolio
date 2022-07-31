<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberFavorMap
 *
 * @property int $no
 * @property int $no_user
 * @property string $cd_favor_map
 * @property string|null $ds_address
 * @property float|null $at_lat
 * @property float|null $at_lng
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberFavorMap extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'at_lat' => 'float',
        'at_lng' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'cd_favor_map',
        'ds_address',
        'at_lat',
        'at_lng',
        'dt_reg'
    ];
}
