<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class SalesAgency
 *
 * @property int $no_sales_agency
 * @property string $nm_sales_agency
 * @property string|null $ds_biz_num
 * @property string|null $cd_sales_contract_type
 * @property float|null $at_sales_commission
 * @property Carbon|null $dt_contract
 * @property string|null $ds_contract_url_1
 * @property string|null $ds_contract_url_2
 * @property string|null $ds_contract_url_3
 * @property string|null $ds_contract_url_4
 * @property string|null $ds_contract_url_5
 * @property string|null $ds_contract_url_6
 * @property string|null $ds_contract_url_7
 * @property string|null $ds_contract_url_8
 * @property string|null $ds_contract_url_9
 * @property string|null $ds_contract_url_10
 * @property string|null $nm_agency_manager
 * @property string|null $ds_agency_tel
 * @property string|null $nm_owin_manager
 * @property string|null $ds_owin_tel
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class SalesAgency extends Model
{
    protected $primaryKey = 'no_sales_agency';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'at_sales_commission' => 'float'
    ];

    protected $dates = [
        'dt_contract',
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'nm_sales_agency',
        'ds_biz_num',
        'cd_sales_contract_type',
        'at_sales_commission',
        'dt_contract',
        'ds_contract_url_1',
        'ds_contract_url_2',
        'ds_contract_url_3',
        'ds_contract_url_4',
        'ds_contract_url_5',
        'ds_contract_url_6',
        'ds_contract_url_7',
        'ds_contract_url_8',
        'ds_contract_url_9',
        'ds_contract_url_10',
        'nm_agency_manager',
        'ds_agency_tel',
        'nm_owin_manager',
        'ds_owin_tel',
        'dt_reg',
        'dt_upt'
    ];
}
