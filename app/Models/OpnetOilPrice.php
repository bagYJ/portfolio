<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OpnetOilPrice
 *
 * @property string $ds_uni
 * @property string $ds_prod
 * @property float|null $at_price
 * @property Carbon|null $dt_trade
 * @property Carbon|null $tm_trade
 *
 * @package App\Models
 */
class OpnetOilPrice extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'at_price' => 'float'
    ];

    protected $dates = [
        'dt_trade',
        'tm_trade'
    ];

    protected $fillable = [
        'at_price',
        'dt_trade',
        'tm_trade'
    ];
}
