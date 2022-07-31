<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdBannerItem
 *
 * @property int $no
 * @property string|null $nm_banner
 * @property int $no_banner_area
 * @property int|null $at_view_banner
 * @property string|null $cd_ad_type
 * @property string|null $yn_inside_link
 * @property string|null $nm_inside_menu
 * @property int|null $no_bbs
 * @property string|null $ds_outer_link
 * @property string|null $ds_banner_img
 * @property string $ds_status
 * @property Carbon|null $dt_open
 * @property Carbon|null $dt_close
 * @property string $del_yn
 * @property Carbon $dt_reg
 * @property string $id_reg
 * @property Carbon|null $dt_upt
 * @property string|null $id_upt
 *
 * @package App\Models
 */
class AdBannerItem extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_banner_area' => 'int',
        'at_view_banner' => 'int',
        'no_bbs' => 'int'
    ];

    protected $dates = [
        'dt_open',
        'dt_close',
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'nm_banner',
        'no_banner_area',
        'at_view_banner',
        'cd_ad_type',
        'yn_inside_link',
        'nm_inside_menu',
        'no_bbs',
        'ds_outer_link',
        'ds_banner_img',
        'ds_status',
        'dt_open',
        'dt_close',
        'del_yn',
        'dt_reg',
        'id_reg',
        'dt_upt',
        'id_upt'
    ];
}
