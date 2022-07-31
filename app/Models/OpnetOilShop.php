<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OpnetOilShop
 *
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
 * @property float|null $at_lat
 * @property float|null $at_lng
 *
 * @package App\Models
 */
class OpnetOilShop extends Model
{
    protected $primaryKey = 'ds_uni';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'at_gis_x' => 'float',
        'at_gis_y' => 'float',
        'at_lat' => 'float',
        'at_lng' => 'float'
    ];

    protected $dates = [
        'dt_mofy'
    ];

    protected $fillable = [
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
        'at_lat',
        'at_lng'
    ];
}
