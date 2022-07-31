<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class WashShopPgLog
 *
 * @property int $no
 * @property int $no_shop
 * @property string $cd_pg
 * @property float $at_pg_commission_rate
 * @property string $id_reg
 * @property Carbon $dt_reg
 *
 * @package App\Models
 */
class WashShopPgLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'at_pg_commission_rate' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'cd_pg',
        'at_pg_commission_rate',
        'id_reg',
        'dt_reg'
    ];
}
