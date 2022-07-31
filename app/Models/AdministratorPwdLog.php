<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdministratorPwdLog
 *
 * @property int $no
 * @property string $id_admin
 * @property Carbon|null $dt_reg
 * @property string $ds_pwd
 *
 * @package App\Models
 */
class AdministratorPwdLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'id_admin',
        'dt_reg',
        'ds_pwd'
    ];
}
