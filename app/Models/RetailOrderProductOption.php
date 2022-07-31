<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RetailOrderProductOption
 *
 * @property int $no
 * @property string $no_order
 * @property int $no_order_product
 * @property int $no_option
 * @property int|null $no_product_opt
 * @property string|null $nm_product_opt
 * @property float|null $at_price_opt
 * @property float|null $at_price_product_opt
 * @property int|null $ct_inven
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class RetailOrderProductOption extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

    protected $casts = [
        'no_order_product' => 'int',
        'no_option' => 'int',
        'no_product_opt' => 'int',
        'at_price_opt' => 'float',
        'at_price_product_opt' => 'float',
        'ct_inven' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_order',
        'no_order_product',
        'no_option',
        'no_product_opt',
        'nm_product_opt',
        'at_price_opt',
        'at_price_product_opt',
        'ct_inven'
    ];

    public function retailProductOption(): BelongsTo
    {
        return $this->belongsTo(RetailProductOption::class, 'no_option', 'no_option');
    }
}
