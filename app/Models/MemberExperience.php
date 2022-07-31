<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberExperience
 *
 * @property int $no
 * @property string $nm_user
 * @property string $ds_phone
 * @property string $ds_phone_2
 * @property string|null $cd_device_cable
 * @property string|null $cd_receive_method
 * @property int|null $no_shop
 * @property string|null $cd_shop_qr
 * @property string|null $ds_post_num
 * @property string|null $ds_addr
 * @property string|null $ds_addr_2
 * @property string|null $ds_memo
 * @property string|null $cd_exp_status
 * @property Carbon|null $dt_exp_start
 * @property Carbon|null $dt_exp_end
 * @property Carbon|null $dt_reg
 * @property string|null $yn_agree
 * @property Carbon|null $dt_upt
 * @property int|null $no_user
 * @property string|null $cd_happy_call
 * @property Carbon|null $dt_happy_call
 * @property string|null $yn_is_experience
 * @property string|null $cd_send_method
 * @property string|null $ds_send_text
 * @property string|null $ds_sn
 * @property string|null $yn_coupon_publish
 * @property string|null $cd_device_return
 * @property Carbon|null $dt_device_return
 * @property string|null $ds_phone_info
 * @property string|null $ds_etc
 *
 * @package App\Models
 */
class MemberExperience extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_exp_start',
        'dt_exp_end',
        'dt_reg',
        'dt_upt',
        'dt_happy_call',
        'dt_device_return'
    ];

    protected $fillable = [
        'nm_user',
        'ds_phone',
        'ds_phone_2',
        'cd_device_cable',
        'cd_receive_method',
        'no_shop',
        'cd_shop_qr',
        'ds_post_num',
        'ds_addr',
        'ds_addr_2',
        'ds_memo',
        'cd_exp_status',
        'dt_exp_start',
        'dt_exp_end',
        'dt_reg',
        'yn_agree',
        'dt_upt',
        'no_user',
        'cd_happy_call',
        'dt_happy_call',
        'yn_is_experience',
        'cd_send_method',
        'ds_send_text',
        'ds_sn',
        'yn_coupon_publish',
        'cd_device_return',
        'dt_device_return',
        'ds_phone_info',
        'ds_etc'
    ];
}
