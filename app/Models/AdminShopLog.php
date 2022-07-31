<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdminShopLog
 *
 * @property int $no
 * @property int $no_shop
 * @property string|null $ds_status
 * @property string|null $cd_contract_status
 * @property string|null $cd_commission_type
 * @property float|null $at_commission_amount
 * @property float|null $at_commission_rate
 * @property string|null $cd_pg
 * @property float|null $at_pg_commission_rate
 * @property int|null $no_sales_agency
 * @property float|null $at_sales_commission_rate
 * @property string|null $cd_pause_type
 * @property string|null $ds_btn_notice
 * @property string $id_admin
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class AdminShopLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'at_commission_amount' => 'float',
        'at_commission_rate' => 'float',
        'at_pg_commission_rate' => 'float',
        'no_sales_agency' => 'int',
        'at_sales_commission_rate' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'ds_status',
        'cd_contract_status',
        'cd_commission_type',
        'at_commission_amount',
        'at_commission_rate',
        'cd_pg',
        'at_pg_commission_rate',
        'no_sales_agency',
        'at_sales_commission_rate',
        'cd_pause_type',
        'ds_btn_notice',
        'id_admin',
        'dt_reg'
    ];
}
