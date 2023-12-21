<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ProductOptionIgnoreHistory
 *
 * @property int $no
 * @property int|null $no_shop
 * @property int|null $no_option
 * @property string|null $id_start
 * @property Carbon|null $dt_start
 * @property string|null $id_stop
 * @property Carbon|null $dt_stop
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ProductOptionIgnoreHistory extends Model
{
    public $timestamps = false;
    protected $table = 'product_option_ignore_history';
    protected $primaryKey = 'no';
    protected $casts = [
        'no_shop' => 'int',
        'no_option' => 'int'
    ];

    protected $dates = [
        'dt_start',
        'dt_stop',
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'no_option',
        'id_start',
        'dt_start',
        'id_stop',
        'dt_stop',
        'id_reg',
        'dt_reg'
    ];
}
