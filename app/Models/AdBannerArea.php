<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdBannerArea
 *
 * @property int $no
 * @property int $no_banner_area
 * @property string $nm_banner_area
 * @property int|null $ds_img_width
 * @property int|null $ds_img_height
 * @property int|null $nt_slot
 * @property string|null $ds_status
 * @property Carbon|null $dt_reg
 * @property string|null $id_reg
 * @property Carbon|null $dt_upt
 * @property string|null $id_upt
 *
 * @package App\Models
 */
class AdBannerArea extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $casts = [
        'no_banner_area' => 'int',
        'ds_img_width' => 'int',
        'ds_img_height' => 'int',
        'nt_slot' => 'int'
    ];

    protected $fillable = [
        'no_banner_area',
        'nm_banner_area',
        'ds_img_width',
        'ds_img_height',
        'nt_slot',
        'ds_status',
        'dt_reg',
        'id_reg',
        'dt_upt',
        'id_upt'
    ];
}
