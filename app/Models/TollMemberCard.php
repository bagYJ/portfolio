<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class TollMemberCard
 *
 * @property int $no_user
 * @property string|null $no_card_user
 * @property string|null $ds_pay_passwd
 * @property string|null $yn_delete
 * @property Carbon|null $dt_del
 * @property Carbon|null $dt_reg
 * @property string|null $yn_agree_event
 * @property string|null $yn_agree_person
 * @property string|null $yn_agree_bill
 * @property string|null $yn_res
 * @property string|null $ds_res_code
 * @property string|null $ds_res_msg
 * @property string|null $yn_del_res
 * @property string|null $ds_del_res_code
 * @property string|null $ds_del_res_msg
 *
 * @package App\Models
 */
class TollMemberCard extends Model
{
    protected $primaryKey = 'no_user';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'no_card_user',
        'ds_pay_passwd',
        'yn_delete',
        'dt_del',
        'dt_reg',
        'yn_agree_event',
        'yn_agree_person',
        'yn_agree_bill',
        'yn_res',
        'ds_res_code',
        'ds_res_msg',
        'yn_del_res',
        'ds_del_res_code',
        'ds_del_res_msg'
    ];
}
