<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PromotionDeal
 *
 * @property int $no
 * @property int|null $no_deal
 * @property string|null $nm_deal
 * @property int|null $at_pin_total
 * @property int|null $at_disct_price
 * @property int|null $at_taget_liter
 * @property int|null $at_disct_limit
 * @property string|null $cd_deal_type
 * @property string|null $ds_index_char
 * @property int|null $no_part_cpn_event
 * @property string|null $cdn_cpn_amt
 * @property Carbon|null $dt_deal_use_st
 * @property Carbon|null $dt_deal_use_end
 * @property Carbon|null $dt_deal_apply_st
 * @property Carbon|null $dt_deal_apply_end
 * @property string|null $ds_gs_sale_code
 * @property string|null $ds_bandwidth_st
 * @property string|null $ds_bandwidth_end
 * @property string|null $yn_single_pin
 * @property Carbon|null $dt_upt
 * @property Carbon|null $dt_reg
 * @property string|null $cd_biz_kind
 * @property int|null $retail_no_event
 * @property int|null $retail_no_event_2
 *
 * @package App\Models
 */
class PromotionDeal extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'no_deal' => 'int',
        'at_pin_total' => 'int',
        'at_disct_price' => 'int',
        'at_taget_liter' => 'int',
        'at_disct_limit' => 'int',
        'no_part_cpn_event' => 'int',
        'retail_no_event' => 'int',
        'retail_no_event_2' => 'int'
    ];

    protected $dates = [
        'dt_deal_use_st',
        'dt_deal_use_end',
        'dt_deal_apply_st',
        'dt_deal_apply_end',
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_deal',
        'nm_deal',
        'at_pin_total',
        'at_disct_price',
        'at_taget_liter',
        'at_disct_limit',
        'cd_deal_type',
        'ds_index_char',
        'no_part_cpn_event',
        'cdn_cpn_amt',
        'dt_deal_use_st',
        'dt_deal_use_end',
        'dt_deal_apply_st',
        'dt_deal_apply_end',
        'ds_gs_sale_code',
        'ds_bandwidth_st',
        'ds_bandwidth_end',
        'yn_single_pin',
        'dt_upt',
        'dt_reg',
        'cd_biz_kind',
        'retail_no_event',
        'retail_no_event_2'
    ];
}
