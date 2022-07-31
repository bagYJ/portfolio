<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PushGroupList
 *
 * @property int $seq
 * @property Carbon $dt_reservation
 * @property Carbon|null $dt_send
 * @property Carbon|null $dt_last_send
 * @property string $message
 * @property string $link_bbs
 * @property int $link_bbs_seq
 * @property int $target_cnt
 * @property int|null $send_cnt
 * @property string $status
 * @property Carbon $dt_reg
 * @property string $id_reg
 * @property string|null $id_del
 *
 * @package App\Models
 */
class PushGroupList extends Model
{
    protected $primaryKey = 'seq';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'link_bbs_seq' => 'int',
        'target_cnt' => 'int',
        'send_cnt' => 'int'
    ];

    protected $dates = [
        'dt_reservation',
        'dt_send',
        'dt_last_send',
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reservation',
        'dt_send',
        'dt_last_send',
        'message',
        'link_bbs',
        'link_bbs_seq',
        'target_cnt',
        'send_cnt',
        'status',
        'dt_reg',
        'id_reg',
        'id_del'
    ];
}
