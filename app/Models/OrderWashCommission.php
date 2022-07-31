<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderWashCommission
 *
 * @property int $no
 * @property string $no_order
 * @property int $no_user
 * @property int $no_shop
 * @property string $cd_biz_kind
 * @property string|null $cd_pg
 * @property int|null $no_commission
 * @property float|null $at_commission
 * @property float|null $at_apply_price
 * @property float|null $at_pg_commission_rate
 * @property float|null $at_price
 * @property float|null $at_cpn_disct
 * @property float|null $at_price_pg
 * @property float|null $at_owin_commission
 * @property float|null $at_owin_vat
 * @property float|null $at_pg_commission
 * @property float|null $at_pg_vat
 * @property float|null $at_cpn_disct_pg_commit
 * @property float|null $at_cpn_disct_pg_vat
 * @property string|null $yn_pg_commission_out
 * @property float|null $at_price_for_shop
 * @property Carbon|null $dt_send
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class OrderWashCommission extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_shop' => 'int',
        'no_commission' => 'int',
        'at_commission' => 'float',
        'at_apply_price' => 'float',
        'at_pg_commission_rate' => 'float',
        'at_price' => 'float',
        'at_cpn_disct' => 'float',
        'at_price_pg' => 'float',
        'at_owin_commission' => 'float',
        'at_owin_vat' => 'float',
        'at_pg_commission' => 'float',
        'at_pg_vat' => 'float',
        'at_cpn_disct_pg_commit' => 'float',
        'at_cpn_disct_pg_vat' => 'float',
        'at_price_for_shop' => 'float'
    ];

    protected $dates = [
        'dt_send',
        'dt_reg'
    ];

    protected $fillable = [
        'no_order',
        'no_user',
        'no_shop',
        'cd_biz_kind',
        'cd_pg',
        'no_commission',
        'at_commission',
        'at_apply_price',
        'at_pg_commission_rate',
        'at_price',
        'at_cpn_disct',
        'at_price_pg',
        'at_owin_commission',
        'at_owin_vat',
        'at_pg_commission',
        'at_pg_vat',
        'at_cpn_disct_pg_commit',
        'at_cpn_disct_pg_vat',
        'yn_pg_commission_out',
        'at_price_for_shop',
        'dt_send',
        'dt_reg'
    ];
}
