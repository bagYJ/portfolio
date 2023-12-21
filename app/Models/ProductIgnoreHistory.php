<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ProductIgnoreHistory
 *
 * @property int $no
 * @property int|null $no_shop
 * @property int|null $no_product
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
class ProductIgnoreHistory extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;


    protected $casts = [
        'no_shop' => 'int',
        'no_product' => 'int'
    ];

    protected $dates = [
        'dt_start',
        'dt_stop',
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'no_product',
        'yn_status',
        'id_start',
        'dt_start',
        'id_stop',
        'dt_stop',
        'id_reg',
        'dt_reg'
    ];
}
