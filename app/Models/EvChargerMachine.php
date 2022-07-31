<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class EvChargerMachine
 *
 * @property string $id_stat
 * @property string $id_chger
 * @property string $cd_chger_stat
 * @property Carbon|null $dt_stat_upd
 * @property Carbon|null $dt_last_start
 * @property Carbon|null $dt_last_end
 * @property Carbon|null $dt_charge_ing
 * @property string $cd_chger_type
 * @property string|null $ds_output
 * @property string|null $ds_method
 * @property string $yn_del
 * @property string|null $ds_del
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class EvChargerMachine extends Model
{
    protected $table = 'ev_charger_machine';
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $dates = [
        'dt_stat_upd',
        'dt_last_start',
        'dt_last_end',
        'dt_charge_ing',
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'cd_chger_stat',
        'dt_stat_upd',
        'dt_last_start',
        'dt_last_end',
        'dt_charge_ing',
        'cd_chger_type',
        'ds_output',
        'ds_method',
        'yn_del',
        'ds_del',
        'dt_reg',
        'dt_upt'
    ];
}
