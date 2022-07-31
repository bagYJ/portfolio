<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class CarListHk
 *
 * @property int $seq
 * @property string|null $yn_korea
 * @property string|null $ds_maker
 * @property string|null $ds_kind
 * @property int $no_maker
 * @property string|null $cd_car_size
 * @property string|null $cd_car_look
 * @property string|null $cd_car_kind
 *
 * @package App\Models
 */
class CarListHk extends Model
{
    protected $primaryKey = 'seq';
    public $timestamps = false;

    protected $casts = [
        'no_maker' => 'int'
    ];

    protected $fillable = [
        'yn_korea',
        'ds_maker',
        'ds_kind',
        'no_maker',
        'cd_car_size',
        'cd_car_look',
        'cd_car_kind'
    ];
}
