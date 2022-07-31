<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class EvCharger
 *
 * @property string $id_stat
 * @property string $nm_stat
 * @property string $ds_addr
 * @property string|null $ds_location
 * @property float $ds_lat
 * @property float $ds_lng
 * @property string $id_busi
 * @property string $nm_bnm
 * @property string $nm_busi
 * @property string $ds_busi_tel
 * @property string $cd_ev_zcode
 * @property string|null $yn_parking_free
 * @property string|null $ds_note
 * @property string $yn_limit
 * @property string|null $ds_limit
 * @property Carbon $dt_reg
 * @property string $ds_use_time
 * @property float|null $at_ev_price
 *
 * @package App\Models
 */
class EvCharger extends Model
{
    protected $table = 'ev_charger';
    protected $primaryKey = 'id_stat';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

    protected $casts = [
        'ds_lat' => 'float',
        'ds_lng' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'nm_stat',
        'ds_addr',
        'ds_location',
        'ds_lat',
        'ds_lng',
        'id_busi',
        'nm_bnm',
        'nm_busi',
        'ds_busi_tel',
        'cd_ev_zcode',
        'yn_parking_free',
        'ds_note',
        'yn_limit',
        'ds_limit',
        'dt_reg',
        'ds_use_time',
        'at_ev_price'
    ];

    public function evChargerMachine(): HasMany
    {
        return $this->hasMany(EvChargerMachine::class, 'id_stat', 'id_stat');
    }
}
