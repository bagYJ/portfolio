<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class CarListTemp
 *
 * @property int $seq
 * @property string $cd_car_kind
 *
 * @package App\Models
 */
class CarListTemp extends Model
{
    protected $primaryKey = 'seq';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'seq' => 'int'
    ];

    protected $fillable = [
        'cd_car_kind'
    ];
}
