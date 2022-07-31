<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopProductOptionGroup
 *
 * @property int $no
 * @property int $no_group
 * @property string|null $nm_group
 * @property int|null $no_shop
 * @property int|null $ct_order
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ShopProductOptionGroup extends Model
{
    protected $primaryKey = 'no_group';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no' => 'int',
        'no_group' => 'int',
        'no_shop' => 'int',
        'ct_order' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'nm_group',
        'no_shop',
        'ct_order',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg'
    ];
}
