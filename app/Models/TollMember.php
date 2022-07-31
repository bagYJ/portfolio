<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class TollMember
 *
 * @property string $ds_phone
 * @property string|null $ds_company
 * @property string|null $ds_car_type
 * @property string|null $cd_toll_member_status
 * @property Carbon|null $dt_upt
 * @property int|null $no_user
 *
 * @package App\Models
 */
class TollMember extends Model
{
    protected $primaryKey = 'ds_phone';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = null;
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_upt'
    ];

    protected $fillable = [
        'ds_company',
        'ds_car_type',
        'cd_toll_member_status',
        'dt_upt',
        'no_user'
    ];
}
