<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ShopOil
 *
 * @property int $no_shop
 * @property string $ds_uni
 * @property string|null $ds_poll_div
 * @property string|null $ds_gpoll_div
 * @property string|null $nm_os
 * @property string|null $ds_van_adr
 * @property string|null $ds_new_adr
 * @property string|null $ds_tel
 * @property float|null $at_gis_x
 * @property float|null $at_gis_y
 * @property string|null $yn_maint
 * @property string|null $yn_cvs
 * @property string|null $yn_car_wash
 * @property string|null $yn_self
 * @property string|null $cd_sido
 * @property string|null $cd_sigun
 * @property string|null $ds_lpg
 * @property Carbon|null $dt_mofy
 * @property Carbon|null $dt_update
 * @property string|null $ds_id_for_bill
 * @property string|null $ds_unit_key_for_bill
 * @property string|null $ds_unit_key_for_bill_nice
 * @property string|null $yn_dp2
 *
 * @package App\Models
 */
class ShopOil extends Model
{
    protected $primaryKey = 'no_shop';
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = null;
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'at_gis_x' => 'float',
        'at_gis_y' => 'float'
    ];

    protected $dates = [
        'dt_mofy',
        'dt_update'
    ];

    protected $fillable = [
        'ds_uni',
        'ds_poll_div',
        'ds_gpoll_div',
        'nm_os',
        'ds_van_adr',
        'ds_new_adr',
        'ds_tel',
        'at_gis_x',
        'at_gis_y',
        'yn_maint',
        'yn_cvs',
        'yn_car_wash',
        'yn_self',
        'cd_sido',
        'cd_sigun',
        'ds_lpg',
        'dt_mofy',
        'dt_update',
        'ds_id_for_bill',
        'ds_unit_key_for_bill',
        'ds_unit_key_for_bill_nice',
        'yn_dp2'
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'no_shop', 'no_shop');
    }
}
