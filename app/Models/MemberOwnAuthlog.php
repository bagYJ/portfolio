<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberOwnAuthlog
 *
 * @property int $no
 * @property int $no_auth_seq
 * @property int|null $no_user
 * @property string|null $ds_udid
 * @property string|null $cd_auth_use
 * @property string|null $yn_nation
 * @property string|null $ds_name
 * @property string|null $ds_phone_agency
 * @property string|null $ds_phone
 * @property string|null $ds_sex
 * @property string|null $ds_birthday
 * @property string|null $ds_cert_num
 * @property string|null $ds_ci
 * @property string|null $ds_di
 * @property string|null $ds_request_ip
 * @property string|null $ds_socket_result1
 * @property string|null $ds_auth_result1
 * @property string|null $ds_request_check1
 * @property string|null $ds_request_check2
 * @property string|null $ds_request_check3
 * @property int|null $ct_request
 * @property string|null $ds_socket_result2
 * @property string|null $ds_auth_result2
 * @property Carbon|null $dt_complate
 * @property int|null $ct_complate
 * @property Carbon|null $dt_use
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberOwnAuthlog extends Model
{
    protected $primaryKey = 'no_auth_seq';
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_auth_seq' => 'string',
        'no_user' => 'int',
        'ct_request' => 'int',
        'ct_complate' => 'int'
    ];

    protected $dates = [
        'dt_complate',
        'dt_use',
        'dt_reg'
    ];

    protected $fillable = [
        'no_auth_seq',
        'no_user',
        'ds_udid',
        'cd_auth_use',
        'yn_nation',
        'ds_name',
        'ds_phone_agency',
        'ds_phone',
        'ds_sex',
        'ds_birthday',
        'ds_cert_num',
        'ds_ci',
        'ds_di',
        'ds_request_ip',
        'ds_socket_result1',
        'ds_auth_result1',
        'ds_request_check1',
        'ds_request_check2',
        'ds_request_check3',
        'ct_request',
        'ds_socket_result2',
        'ds_auth_result2',
        'dt_complate',
        'ct_complate',
        'dt_use',
        'dt_reg'
    ];
}
