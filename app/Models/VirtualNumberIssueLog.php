<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class VirtualNumberIssueLog
 *
 * @property int $no
 * @property string $real_number
 * @property string|null $virtual_number
 * @property int|null $no_user
 * @property string|null $no_order
 * @property Carbon|null $dt_use_start
 * @property Carbon|null $dt_use_end
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $yn_success
 * @property string|null $fail_reason
 *
 * @package App\Models
 */
class VirtualNumberIssueLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_use_start',
        'dt_use_end',
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'real_number',
        'virtual_number',
        'no_user',
        'no_order',
        'dt_use_start',
        'dt_use_end',
        'dt_reg',
        'dt_upt',
        'yn_success',
        'fail_reason'
    ];
}
