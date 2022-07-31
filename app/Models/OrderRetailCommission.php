<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderRetailCommission
 *
 * @property int $no
 * @property string $no_order
 * @property int $no_user
 * @property int|null $no_partner
 * @property int|null $no_shop
 * @property string|null $cd_biz_kind
 * @property string|null $cd_pg
 * @property string|null $cd_calculate_main
 * @property float|null $at_commission_rate
 * @property float|null $at_send_price
 * @property float|null $at_price
 * @property float|null $at_price_pg
 * @property float|null $at_target_price
 * @property float|null $at_cpn_disct
 * @property float|null $at_cpn_disct_cu_self
 * @property float|null $at_cpn_disct_cu
 * @property float|null $at_cpn_disct_owin
 * @property float|null $at_cpn_disct_owin_commit
 * @property float|null $at_cpn_disct_owin_vat
 * @property float|null $at_owin_commission
 * @property float|null $at_owin_vat
 * @property float|null $at_commission_total
 * @property float|null $at_price_for_shop
 * @property Carbon|null $dt_send
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class OrderRetailCommission extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_partner' => 'int',
        'no_shop' => 'int',
        'at_commission_rate' => 'float',
        'at_send_price' => 'float',
        'at_price' => 'float',
        'at_price_pg' => 'float',
        'at_target_price' => 'float',
        'at_cpn_disct' => 'float',
        'at_cpn_disct_cu_self' => 'float',
        'at_cpn_disct_cu' => 'float',
        'at_cpn_disct_owin' => 'float',
        'at_cpn_disct_owin_commit' => 'float',
        'at_cpn_disct_owin_vat' => 'float',
        'at_owin_commission' => 'float',
        'at_owin_vat' => 'float',
        'at_commission_total' => 'float',
        'at_price_for_shop' => 'float'
    ];

    protected $dates = [
        'dt_send',
        'dt_reg'
    ];

    protected $fillable = [
        'no_order',
        'no_user',
        'no_partner',
        'no_shop',
        'cd_biz_kind',
        'cd_pg',
        'cd_calculate_main',
        'at_commission_rate',
        'at_send_price',
        'at_price',
        'at_price_pg',
        'at_target_price',
        'at_cpn_disct',
        'at_cpn_disct_cu_self',
        'at_cpn_disct_cu',
        'at_cpn_disct_owin',
        'at_cpn_disct_owin_commit',
        'at_cpn_disct_owin_vat',
        'at_owin_commission',
        'at_owin_vat',
        'at_commission_total',
        'at_price_for_shop',
        'dt_send',
        'dt_reg'
    ];
}
