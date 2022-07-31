<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Administrator
 *
 * @property int $no
 * @property string $id_admin
 * @property string|null $nm_admin
 * @property string|null $cd_level
 * @property string|null $cd_admin_type
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property string|null $ds_pwd
 * @property Carbon|null $dt_pwd_chg
 * @property string|null $ds_email
 *
 * @package App\Models
 */
class Administrator extends Model
{
    protected $primaryKey = 'id_admin';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_del',
        'dt_reg',
        'dt_pwd_chg'
    ];

    protected $fillable = [
        'no',
        'nm_admin',
        'cd_level',
        'cd_admin_type',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg',
        'ds_pwd',
        'dt_pwd_chg',
        'ds_email'
    ];
}
