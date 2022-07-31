<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderLocation
 *
 * @property string $no_order
 * @property float|null $at_lat_curr
 * @property float|null $at_lng_curr
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class OrderLocation extends Model
{
    protected $primaryKey = 'no_order';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'at_lat_curr' => 'float',
        'at_lng_curr' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'at_lat_curr',
        'at_lng_curr',
        'no_order',
        'dt_reg'
    ];
}
