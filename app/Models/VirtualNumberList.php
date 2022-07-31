<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class VirtualNumberList
 *
 * @property int $no
 * @property string $virtual_number
 * @property string $yn_possible
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class VirtualNumberList extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'virtual_number',
        'yn_possible',
        'dt_reg',
        'dt_upt'
    ];
}
