<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class GsSaleOrderList
 *
 * @property int $no
 * @property string|null $id_pointcard
 * @property string|null $ds_save_type
 * @property string|null $ds_sale_type
 * @property string|null $ds_oil_shop
 * @property float|null $at_sale_amt
 * @property float|null $at_point
 * @property string|null $ds_goods_name
 * @property string|null $dt_order
 * @property Carbon|null $dt_reg
 * @property string|null $dt_start
 * @property string|null $dt_end
 *
 * @package App\Models
 */
class GsSaleOrderList extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;


    protected $casts = [
        'at_sale_amt' => 'float',
        'at_point' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'id_pointcard',
        'ds_save_type',
        'ds_sale_type',
        'ds_oil_shop',
        'at_sale_amt',
        'at_point',
        'ds_goods_name',
        'dt_order',
        'dt_reg',
        'dt_start',
        'dt_end'
    ];
}
