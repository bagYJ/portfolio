<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdminInstaller
 *
 * @property string $id_admin
 * @property string|null $ds_pwd
 * @property string|null $nm_admin
 * @property string|null $ds_tel
 * @property string|null $yn_status
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class AdminInstaller extends Model
{
    protected $primaryKey = 'id_admin';
    public $incrementing = false;
    public $timestamps = false;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'ds_pwd',
        'nm_admin',
        'ds_tel',
        'yn_status',
        'dt_reg',
        'dt_upt'
    ];
}
