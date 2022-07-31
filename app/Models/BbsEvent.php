<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class BbsEvent
 *
 * @property int $no
 * @property string|null $id_admin
 * @property string|null $ds_title
 * @property string|null $ds_content
 * @property string $cd_event_target
 * @property string|null $ds_thumb
 * @property Carbon|null $dt_start
 * @property Carbon|null $dt_end
 * @property string|null $yn_show
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property string|null $cd_service
 * @property string|null $ds_popup_thumb
 * @property string|null $yn_popup
 * @property string|null $yn_prior_popup
 * @property string|null $yn_move_button
 * @property string|null $ds_banner_thumb
 * @property string|null $yn_banner
 * @property string|null $ds_detail_url
 * @property int|null $no_part_cpn_event
 * @property int|null $no_event
 * @property Carbon|null $dt_event_start
 * @property string $link_act
 * @property int $no_shop
 * @property int $no_product
 *
 * @package App\Models
 */
class BbsEvent extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    protected $casts = [
        'no_part_cpn_event' => 'int',
        'no_event' => 'int',
        'no_shop' => 'int'
    ];

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = 'dt_del';

    protected $dates = [
        'dt_start',
        'dt_end',
        'dt_upt',
        'dt_del',
        'dt_reg',
//		'dt_event_start'
    ];

    protected $fillable = [
        'id_admin',
        'ds_title',
        'ds_content',
        'cd_event_target',
        'ds_thumb',
        'dt_start',
        'dt_end',
        'yn_show',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg',
        'cd_service',
        'ds_popup_thumb',
        'yn_popup',
        'yn_prior_popup',
        'yn_move_button',
        'ds_banner_thumb',
        'yn_banner',
        'ds_detail_url',
        'no_part_cpn_event',
        'no_event',
        'dt_event_start',
        'link_act',
        'no_shop',
        'no_product'
    ];

    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class, 'no_shop', 'no_shop');
    }
}
