<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberCardRequest
 *
 * @property int $no
 * @property int|null $no_user
 * @property string|null $ds_res_url
 * @property string|null $ds_fdk_hash
 * @property string|null $ds_owin_hash
 * @property string|null $ds_phone_hash
 * @property string|null $cd_card_regist
 * @property string|null $ds_res_param
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberCardRequest extends Model
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
        'ds_res_url',
        'ds_fdk_hash',
        'ds_owin_hash',
        'ds_phone_hash',
        'cd_card_regist',
        'ds_res_param',
        'dt_reg'
    ];
}
