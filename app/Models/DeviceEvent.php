<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class DeviceEvent
 *
 * @property string $ds_sn
 * @property string|null $nm_event
 *
 * @package App\Models
 */
class DeviceEvent extends Model
{
    protected $primaryKey = 'ds_sn';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nm_event'
    ];
}
