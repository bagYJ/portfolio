<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberEventLog
 *
 * @property int $no
 * @property int $no_seq
 * @property int $no_user
 * @property string|null $ds_status
 * @property string|null $ds_join_path
 * @property Carbon|null $dt_join_reg
 * @property Carbon|null $dt_start
 * @property Carbon|null $dt_end
 * @property string|null $yn_show
 * @property int|null $no_part_cpn_event
 * @property int|null $no_event
 * @property string|null $ds_cpn_no
 * @property Carbon|null $dt_cpn_reg
 * @property string|null $ds_try_result_code
 * @property string|null $ds_try_result_msg
 * @property string|null $ds_result_code
 * @property string|null $ds_result_msg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberEventLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_seq' => 'int',
        'no_user' => 'int',
        'no_part_cpn_event' => 'int',
        'no_event' => 'int'
    ];

    protected $dates = [
        'dt_join_reg',
        'dt_start',
        'dt_end',
        'dt_cpn_reg',
        'dt_reg'
    ];

    protected $fillable = [
        'no_seq',
        'no_user',
        'ds_status',
        'ds_join_path',
        'dt_join_reg',
        'dt_start',
        'dt_end',
        'yn_show',
        'no_part_cpn_event',
        'no_event',
        'ds_cpn_no',
        'dt_cpn_reg',
        'ds_try_result_code',
        'ds_try_result_msg',
        'ds_result_code',
        'ds_result_msg',
        'dt_reg'
    ];
}
