<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class RetailOrderProduct
 *
 * @property int $no
 * @property string $no_order
 * @property int $no_order_product
 * @property int $no_product
 * @property string|null $nm_product
 * @property float|null $at_price
 * @property float|null $at_price_product
 * @property float|null $at_price_option
 * @property string|null $cd_discount_sale
 * @property int|null $ct_inven
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class RetailOrderProduct extends Model
{
    use Compoships;

    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

    protected $casts = [
        'no_order_product' => 'int',
        'no_product' => 'int',
        'at_price' => 'float',
        'at_price_product' => 'float',
        'at_price_option' => 'float',
        'ct_inven' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_order',
        'no_order_product',
        'no_product',
        'nm_product',
        'at_price',
        'at_price_product',
        'at_price_option',
        'cd_discount_sale',
        'ct_inven',
        'dt_reg'
    ];

    public function retailOrderProductOption(): HasMany
    {
        return $this->hasMany(RetailOrderProductOption::class, 'no_order_product', 'no_order_product');
    }

    public function retailProduct(): BelongsTo
    {
        return $this->belongsTo(RetailProduct::class, 'no_product', 'no_product');
    }
}
