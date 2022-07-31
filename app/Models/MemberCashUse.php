<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberCashUse
 *
 * @property int $no
 * @property int|null $no_user
 * @property float|null $at_cash
 * @property float|null $at_event_cash
 * @property float|null $at_cash_aft
 * @property float|null $at_event_cash_aft
 * @property Carbon|null $dt_reg
 * @property string|null $cd_cash_reason
 * @property string|null $id_admin
 * @property string|null $ds_reason
 * @property string|null $ds_etc
 * @property string|null $cd_bank
 * @property string|null $ds_bank_acct
 * @property string|null $nm_acct_name
 * @property string|null $cd_cash_reward_status
 * @property Carbon|null $dt_reward
 *
 * @package App\Models
 */
class MemberCashUse extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'at_cash' => 'float',
        'at_event_cash' => 'float',
        'at_cash_aft' => 'float',
        'at_event_cash_aft' => 'float'
    ];

    protected $dates = [
        'dt_reg',
        'dt_reward'
    ];

    protected $fillable = [
        'no_user',
        'at_cash',
        'at_event_cash',
        'at_cash_aft',
        'at_event_cash_aft',
        'dt_reg',
        'cd_cash_reason',
        'id_admin',
        'ds_reason',
        'ds_etc',
        'cd_bank',
        'ds_bank_acct',
        'nm_acct_name',
        'cd_cash_reward_status',
        'dt_reward'
    ];
}
