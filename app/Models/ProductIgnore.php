<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ProductIgnore
 *
 * @property int $no
 * @property int $no_shop
 * @property int $no_product
 * @property string|null $yn_status
 * @property string|null $id_start
 * @property Carbon|null $dt_start
 * @property string|null $id_stop
 * @property Carbon|null $dt_stop
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ProductIgnore extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;


    protected $casts = [
        'no' => 'int',
        'no_shop' => 'int',
        'no_product' => 'int'
    ];

    protected $dates = [
        'dt_start',
        'dt_stop',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'yn_status_car',
        'yn_status_shop',
        'id_start',
        'dt_start',
        'id_stop',
        'dt_stop',
        'id_reg',
        'dt_reg'
    ];
}
