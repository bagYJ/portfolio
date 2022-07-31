<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopOilLog
 *
 * @property int $no
 * @property int|null $no_shop
 * @property string|null $ds_id_for_bill
 * @property string|null $ds_unit_key_for_bill
 * @property string|null $ds_unit_key_for_bill_nice
 * @property string|null $nm_owner
 * @property string|null $ds_biz_num
 * @property string|null $ds_franchise_num
 * @property string|null $nm_shop_franchise
 * @property Carbon|null $dt_reg
 * @property string|null $ds_op_type
 * @property string|null $id_admin
 *
 * @package App\Models
 */
class ShopOilLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'ds_id_for_bill',
        'ds_unit_key_for_bill',
        'ds_unit_key_for_bill_nice',
        'nm_owner',
        'ds_biz_num',
        'ds_franchise_num',
        'nm_shop_franchise',
        'dt_reg',
        'ds_op_type',
        'id_admin'
    ];
}
