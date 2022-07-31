<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class MemberCarinfo
 *
 * @property int $no
 * @property int $no_user
 * @property int|null $seq
 * @property string|null $ds_etc_kind
 * @property string $ds_car_number
 * @property string|null $ds_car_color
 * @property string|null $ds_car_search
 * @property string|null $cd_gas_kind
 * @property string|null $ds_chk_rssi_where
 * @property int $no_device
 * @property string|null $ds_adver
 * @property string|null $yn_use_auto_parking
 * @property string|null $yn_main_car
 * @property string|null $yn_delete
 * @property string|null $ds_sn
 * @property int|null $no_card
 * @property Carbon|null $dt_auto_parking
 * @property Carbon|null $dt_device_update
 * @property Carbon|null $dt_upt
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberCarinfo extends Model
{
    use Compoships;

    protected $primaryKey = 'no';

    public $incrementing = true;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'seq' => 'int',
        'no_device' => 'int',
        'no_card' => 'int'
    ];

    protected $dates = [
        'dt_auto_parking',
		'dt_device_update',
		'dt_upt',
		'dt_reg'
	];

	protected $fillable = [
        'no_user',
        'seq',
        'ds_etc_kind',
        'ds_car_number',
        'ds_car_color',
        'ds_car_search',
        'cd_gas_kind',
        'ds_chk_rssi_where',
        'no_device',
        'ds_adver',
        'yn_use_auto_parking',
        'yn_main_car',
        'yn_delete',
        'ds_sn',
        'no_card',
        'dt_auto_parking',
        'dt_device_update',
        'dt_upt',
        'dt_reg'
    ];

    protected static function booted()
    {
        static::addGlobalScope('yn_delete_n', function (Builder $builder) {
            $builder->where('yn_delete', 'N');
        });
    }

    public function carList(): BelongsTo
    {
        return $this->BelongsTo(CarList::class, 'seq', 'seq');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(MemberCard::class, 'no_card', 'no_card');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'no_user', 'no_user');
    }
}
