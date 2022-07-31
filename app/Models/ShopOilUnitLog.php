<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopOilUnitLog
 *
 * @property int $no
 * @property int|null $no_shop
 * @property string|null $ds_display_ark_id
 * @property string|null $ds_unit_id
 * @property string|null $cd_oil_marker
 * @property int|null $nt_list_order
 * @property Carbon|null $dt_reg
 * @property string|null $ds_op_type
 * @property string|null $id_admin
 *
 * @package App\Models
 */
class ShopOilUnitLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'nt_list_order' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'ds_display_ark_id',
        'ds_unit_id',
        'cd_oil_marker',
        'nt_list_order',
        'dt_reg',
        'ds_op_type',
        'id_admin'
    ];
}
