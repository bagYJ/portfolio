<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class RetailAdminChkLog
 *
 * @property int $seq
 * @property string $no_order
 * @property int $no_user
 * @property int $no_shop
 * @property string $log_type
 * @property string|null $content
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 * @property int|null $id_admin
 *
 * @package App\Models
 */
class RetailAdminChkLog extends Model
{
    protected $primaryKey = 'seq';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;


    protected $casts = [
        'no_user' => 'int',
        'no_shop' => 'int',
        'id_admin' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no_order',
        'no_user',
        'no_shop',
        'log_type',
        'content',
        'dt_reg',
        'dt_upt',
        'id_admin'
    ];
}
