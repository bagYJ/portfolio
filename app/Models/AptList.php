<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AptList
 *
 * @property int $no
 * @property string $id_apt
 * @property string $nm_apt
 * @property string|null $yn_status
 * @property float|null $at_lat
 * @property float|null $at_lng
 * @property string $cd_event_target
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class AptList extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'at_lat' => 'float',
        'at_lng' => 'float'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'id_apt',
        'nm_apt',
        'yn_status',
        'at_lat',
        'at_lng',
        'cd_event_target',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg'
    ];
}
