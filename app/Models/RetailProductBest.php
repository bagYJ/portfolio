<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class RetailProductBest
 *
 * @property int $no
 * @property int $no_product
 * @property int $no_partner
 * @property int|null $at_view
 * @property string|null $ds_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class RetailProductBest extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_product' => 'int',
        'no_partner' => 'int',
        'at_view' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_product',
        'no_partner',
        'at_view',
        'ds_status',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg'
    ];
}
