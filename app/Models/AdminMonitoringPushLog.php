<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdminMonitoringPushLog
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property int|null $order_err_cnt
 * @property string|null $yn_send
 * @property string|null $id_admin
 * @property string|null $nm_admin
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class AdminMonitoringPushLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'order_err_cnt' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'dt_reg',
        'order_err_cnt',
        'yn_send',
        'id_admin',
        'nm_admin',
        'dt_upt'
    ];
}
