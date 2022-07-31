<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PushLog
 *
 * @property int $seq
 * @property string|null $cd_biz_kind
 * @property int $no_user
 * @property string $no_order
 * @property string|null $message
 * @property Carbon|null $dt_send
 * @property Carbon $dt_reg
 * @property string|null $id_reg
 *
 * @package App\Models
 */
class PushLog extends Model
{
    protected $primaryKey = 'seq';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_send',
        'dt_reg'
    ];

    protected $fillable = [
        'cd_biz_kind',
        'no_user',
        'no_order',
        'message',
        'dt_send',
        'dt_reg',
        'id_reg'
    ];
}
