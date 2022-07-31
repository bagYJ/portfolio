<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberInstaller
 *
 * @property int $no_user
 * @property string|null $id_user
 * @property string|null $cd_install_biz
 * @property string|null $cd_manager_level
 * @property string|null $yn_status
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberInstaller extends Model
{
    protected $primaryKey = 'no_user';
    public $incrementing = false;
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
        'id_user',
        'cd_install_biz',
        'cd_manager_level',
        'yn_status',
        'dt_reg'
    ];
}
