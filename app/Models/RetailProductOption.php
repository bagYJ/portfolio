<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class RetailProductOption
 *
 * @property int $no
 * @property int $no_option
 * @property int $no_partner
 * @property int $no_group
 * @property string|null $no_barcode_opt
 * @property int|null $no_product_opt
 * @property string|null $nm_product_opt
 * @property float|null $at_price_opt
 * @property string|null $ds_status
 * @property int|null $at_view
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class RetailProductOption extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'no_option' => 'int',
        'no_partner' => 'int',
        'no_group' => 'int',
        'no_product_opt' => 'int',
        'at_price_opt' => 'float',
        'at_view' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_option',
        'no_partner',
        'no_group',
        'no_barcode_opt',
        'no_product_opt',
        'nm_product_opt',
        'at_price_opt',
        'ds_status',
        'at_view',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg'
    ];

    protected static function booted()
    {
        static::addGlobalScope('status_y', function (Builder $builder) {
            $builder->where('ds_status', 'Y');
        });
    }
}
