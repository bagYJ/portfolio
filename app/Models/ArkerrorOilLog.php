<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ArkerrorOilLog
 *
 * @property int $no
 * @property string|null $no_partner
 * @property string $no_shop
 * @property string|null $arr_no_shop_ark
 * @property string|null $arr_ark_status
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ArkerrorOilLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_partner',
        'no_shop',
        'arr_no_shop_ark',
        'arr_ark_status',
        'dt_reg'
    ];
}
