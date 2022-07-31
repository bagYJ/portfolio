<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberEventCash
 *
 * @property int $no
 * @property int $no_charge
 * @property int|null $no_user
 * @property float|null $at_cash
 * @property Carbon|null $dt_expire
 * @property float|null $at_cash_aft
 * @property float|null $at_event_cash_aft
 * @property Carbon|null $dt_reg
 * @property string|null $cd_cash_reason
 * @property string|null $id_admin
 * @property string|null $ds_reason
 * @property string|null $ds_etc
 *
 * @package App\Models
 */
class MemberEventCash extends Model
{
    protected $primaryKey = 'no_charge';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_charge' => 'int',
        'no_user' => 'int',
        'at_cash' => 'float',
        'at_cash_aft' => 'float',
        'at_event_cash_aft' => 'float'
    ];

    protected $dates = [
        'dt_expire',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'no_user',
        'at_cash',
        'dt_expire',
        'at_cash_aft',
        'at_event_cash_aft',
        'dt_reg',
        'cd_cash_reason',
        'id_admin',
        'ds_reason',
        'ds_etc'
    ];
}
