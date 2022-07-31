<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdminValet
 *
 * @property int $no
 * @property string $id_admin
 * @property string $ds_passwd
 * @property string|null $nm_admin
 * @property string|null $ds_sex
 * @property string|null $ds_tel
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $cd_admin_valet_status
 * @property int $no_shop
 * @property string|null $cd_phone_os
 * @property string|null $ds_phone_token
 * @property string|null $ds_udid
 * @property int $no_partner
 *
 * @package App\Models
 */
class AdminValet extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    protected $casts = [
        'no' => 'int',
        'no_shop' => 'int',
        'no_partner' => 'int'
    ];

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $hidden = [
        'ds_phone_token'
    ];

    protected $fillable = [
        'no',
        'ds_passwd',
        'nm_admin',
        'ds_sex',
        'ds_tel',
        'dt_reg',
        'dt_upt',
        'cd_admin_valet_status',
        'no_shop',
        'cd_phone_os',
        'ds_phone_token',
        'ds_udid'
    ];
}
