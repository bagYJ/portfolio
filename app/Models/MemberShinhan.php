<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberShinhan
 *
 * @property int $no
 * @property string|null $nm_user
 * @property string|null $ds_phone
 * @property string|null $ds_email
 * @property string|null $cd_phone_os
 * @property string|null $yn_usb_c_type
 * @property string|null $ds_post_num
 * @property string|null $ds_addr
 * @property string|null $ds_sido
 * @property string|null $ds_gugun
 * @property string|null $ds_deli_post_num
 * @property string|null $ds_deli_addr
 * @property string|null $ds_deli_sido
 * @property string|null $ds_deli_gugun
 * @property string|null $yn_agree
 * @property string|null $cd_reg_flow
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $ds_ci
 * @property string|null $yn_is_card
 * @property string|null $yn_is_published
 * @property string|null $ds_cancel_reason
 * @property string|null $yn_is_cancel
 *
 * @package App\Models
 */
class MemberShinhan extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'nm_user',
        'ds_phone',
        'ds_email',
        'cd_phone_os',
        'yn_usb_c_type',
        'ds_post_num',
        'ds_addr',
        'ds_sido',
        'ds_gugun',
        'ds_deli_post_num',
        'ds_deli_addr',
        'ds_deli_sido',
        'ds_deli_gugun',
        'yn_agree',
        'cd_reg_flow',
        'dt_reg',
        'dt_upt',
        'ds_ci',
        'yn_is_card',
        'yn_is_published',
        'ds_cancel_reason',
        'yn_is_cancel'
    ];
}
