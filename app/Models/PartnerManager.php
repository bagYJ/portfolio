<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PartnerManager
 *
 * @property int $no
 * @property string $id_manager
 * @property string|null $nm_manager
 * @property string|null $ds_passwd
 * @property string|null $cd_manager_level
 * @property string|null $ds_status
 * @property int|null $no_partner
 * @property int|null $no_shop
 * @property int|null $ct_login
 * @property Carbon|null $dt_last_login
 * @property string|null $ds_login_ip
 * @property Carbon|null $dt_reg
 * @property string|null $ds_email
 * @property int|null $no_company
 * @property string|null $ds_tel
 * @property string|null $yn_first_login
 * @property string|null $ds_udid
 *
 * @package App\Models
 */
class PartnerManager extends Model
{
    protected $primaryKey = 'id_manager';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;


    protected $casts = [
        'no' => 'int',
        'no_partner' => 'int',
        'no_shop' => 'int',
        'ct_login' => 'int',
        'no_company' => 'int'
    ];

    protected $dates = [
        'dt_last_login',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'nm_manager',
        'ds_passwd',
        'cd_manager_level',
        'ds_status',
        'no_partner',
        'no_shop',
        'ct_login',
        'dt_last_login',
        'ds_login_ip',
        'dt_reg',
        'ds_email',
        'no_company',
        'ds_tel',
        'yn_first_login',
        'ds_udid'
    ];
}
