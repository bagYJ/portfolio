<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OpnetOilPriceHistory
 *
 * @property Carbon $dt_batch
 * @property string $ds_uni
 * @property string $ds_prod
 * @property float|null $at_price
 * @property Carbon|null $dt_trade
 * @property Carbon|null $tm_trade
 *
 * @package App\Models
 */
class OpnetOilPriceHistory extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'at_price' => 'float'
    ];

    protected $dates = [
        'dt_batch',
        'dt_trade',
        'tm_trade'
    ];

    protected $fillable = [
        'at_price',
        'dt_trade',
        'tm_trade'
    ];
}
