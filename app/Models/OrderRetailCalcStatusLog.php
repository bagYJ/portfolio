<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderRetailCalcStatusLog
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property string $group_key
 * @property string $id_admin
 * @property int $no_partner
 * @property string $no_order
 * @property string $date_ym
 * @property string $action_type
 * @property string $yn_result
 *
 * @package App\Models
 */
class OrderRetailCalcStatusLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_partner' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg',
        'group_key',
        'id_admin',
        'no_partner',
        'no_order',
        'date_ym',
        'action_type',
        'yn_result'
    ];
}
