<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ArkHistory
 *
 * @property int $no
 * @property string $ds_sn
 * @property int|null $no_shop
 * @property string|null $no_shop_ark
 * @property int|null $no_install
 * @property string|null $ds_reason
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ArkHistory extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'no_install' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'ds_sn',
        'no_shop',
        'no_shop_ark',
        'no_install',
        'ds_reason',
        'dt_reg'
    ];
}
