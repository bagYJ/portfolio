<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopReceiveCarid
 *
 * @property int $no_shop
 * @property string $yn_can_received
 * @property Carbon $dt_reg
 * @property string|null $id_reg
 * @property Carbon|null $dt_upt
 * @property string|null $id_upt
 *
 * @package App\Models
 */
class ShopReceiveCarid extends Model
{
    protected $primaryKey = 'no_shop';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'yn_can_received',
        'dt_reg',
        'id_reg',
        'dt_upt',
        'id_upt'
    ];
}
