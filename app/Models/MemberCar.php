<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberCar
 *
 * @property int $no
 * @property int|null $seq
 * @property int|null $no_user
 * @property string|null $ds_car_number
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberCar extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'seq' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'seq',
        'no_user',
        'ds_car_number',
        'dt_reg'
    ];
}
