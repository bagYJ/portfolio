<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberPointUse
 *
 * @property int $no
 * @property int|null $no_user
 * @property int|null $at_point
 * @property string|null $cd_point_cp
 * @property Carbon|null $dt_reg
 * @property string|null $yn_cancel
 * @property string|null $no_order
 *
 * @package App\Models
 */
class MemberPointUse extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'at_point' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'at_point',
        'cd_point_cp',
        'dt_reg',
        'yn_cancel',
        'no_order'
    ];
}
