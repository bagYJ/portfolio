<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class GsCpnEvent
 *
 * @property int $no_part_cpn_event
 * @property string $ds_cpn_title
 * @property string $cdn_cpn_amt
 * @property string $cdn_cpn_reason
 * @property string $cdn_cpn_push_home
 * @property int|null $no_bbs_event
 * @property int $at_expire_day
 * @property string $ds_push_msg
 * @property string|null $dt_monthly
 * @property int|null $at_condi_liter
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $cd_third_party
 *
 * @package App\Models
 */
class GsCpnEvent extends Model
{
    protected $primaryKey = 'no_part_cpn_event';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_bbs_event' => 'int',
        'at_expire_day' => 'int',
        'at_condi_liter' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'ds_cpn_title',
        'cdn_cpn_amt',
        'cdn_cpn_reason',
        'cdn_cpn_push_home',
        'no_bbs_event',
        'at_expire_day',
        'ds_push_msg',
        'dt_monthly',
        'at_condi_liter',
        'dt_reg',
        'dt_upt',
        'cd_third_party'
    ];
}
