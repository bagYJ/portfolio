<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberGroup
 *
 * @property int $no
 * @property int $no_user
 * @property string|null $ds_phone
 * @property string|null $id_user
 * @property string|null $cd_mem_group
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberGroup extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'ds_phone',
        'id_user',
        'cd_mem_group',
        'dt_reg'
    ];
}
