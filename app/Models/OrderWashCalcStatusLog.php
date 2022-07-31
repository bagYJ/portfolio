<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderWashCalcStatusLog
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property string $group_key
 * @property string $id_admin
 * @property int $no_shop
 * @property string $no_order
 * @property string $date_ym
 * @property string $action_type
 * @property string $yn_result
 *
 * @package App\Models
 */
class OrderWashCalcStatusLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg',
        'group_key',
        'id_admin',
        'no_shop',
        'no_order',
        'date_ym',
        'action_type',
        'yn_result'
    ];
}
