<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class GsSaleCard
 *
 * @property string $id_pointcard
 * @property int $no_user
 * @property string|null $ds_sale_code
 * @property string|null $ds_cvc
 * @property Carbon|null $dt_reg
 * @property string|null $ds_publisher
 * @property string|null $ds_publisher_code
 * @property string|null $yn_used
 * @property float|null $at_limit_price
 * @property string|null $ds_validity
 * @property float|null $at_limit_one_use
 * @property float|null $at_limit_total_use
 * @property string|null $ds_card_name
 * @property string|null $ds_status_name
 * @property string|null $ds_status_code
 * @property string|null $ds_type_name
 * @property string|null $ds_type_code
 * @property string|null $ds_sale_start
 * @property string|null $ds_sale_end
 * @property float|null $at_save_amt
 * @property float|null $at_can_save_amt
 * @property float|null $at_can_save_total
 * @property string|null $ds_expire_date
 * @property string|null $ds_expire_time
 * @property string|null $yn_sale_status
 * @property string|null $ds_sale_name
 * @property string|null $yn_can_save
 *
 * @package App\Models
 */
class GsSaleCard extends Model
{
    protected $primaryKey = 'id_pointcard';
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'at_limit_price' => 'float',
        'at_limit_one_use' => 'float',
        'at_limit_total_use' => 'float',
        'at_save_amt' => 'float',
        'at_can_save_amt' => 'float',
        'at_can_save_total' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'id_pointcard',
        'no_user',
        'ds_sale_code',
        'ds_cvc',
        'dt_reg',
        'ds_publisher',
        'ds_publisher_code',
        'yn_used',
        'at_limit_price',
        'ds_validity',
        'at_limit_one_use',
        'at_limit_total_use',
        'ds_card_name',
        'ds_status_name',
        'ds_status_code',
        'ds_type_name',
        'ds_type_code',
        'ds_sale_start',
        'ds_sale_end',
        'at_save_amt',
        'at_can_save_amt',
        'at_can_save_total',
        'ds_expire_date',
        'ds_expire_time',
        'yn_sale_status',
        'ds_sale_name',
        'yn_can_save'
    ];
}
