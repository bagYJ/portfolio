<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberDealRefundLog
 *
 * @property int $no
 * @property int $no_user
 * @property string $no_pin
 * @property int $no_deal
 * @property string $yn_pointcard_issue
 * @property Carbon|null $dt_pointcard_reg
 * @property string|null $ds_pointcard_reg_msg
 * @property Carbon|null $dt_deal_use_end
 * @property Carbon|null $dt_ins_reg
 * @property Carbon|null $dt_reg
 * @property string|null $id_admin
 *
 * @package App\Models
 */
class MemberDealRefundLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_deal' => 'int'
    ];

    protected $dates = [
        'dt_pointcard_reg',
        'dt_deal_use_end',
        'dt_ins_reg',
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_pin',
        'no_deal',
        'yn_pointcard_issue',
        'dt_pointcard_reg',
        'ds_pointcard_reg_msg',
        'dt_deal_use_end',
        'dt_ins_reg',
        'dt_reg',
        'id_admin'
    ];


}
