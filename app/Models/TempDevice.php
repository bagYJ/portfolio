<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class TempDevice
 *
 * @property string $ds_sn
 * @property string|null $cd_device_opt
 * @property string|null $cd_distribute
 *
 * @package App\Models
 */
class TempDevice extends Model
{
    protected $primaryKey = 'ds_sn';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'cd_device_opt',
        'cd_distribute'
    ];
}
