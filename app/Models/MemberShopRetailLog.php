<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberShopRetailLog
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property int|null $no_user
 * @property int|null $no_shop
 * @property string|null $no_order
 * @property string|null $cd_alarm_event_type
 * @property string|null $result_code
 * @property string|null $result_msg
 * @property string|null $id_admin
 *
 * @package App\Models
 */
class MemberShopRetailLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg',
        'no_user',
        'no_shop',
        'no_order',
        'cd_alarm_event_type',
        'result_code',
        'result_msg',
        'id_admin'
    ];
}
