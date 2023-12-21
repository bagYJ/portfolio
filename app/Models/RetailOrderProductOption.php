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
 * @property RetailProductOption $retailProductOption
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

	protected $casts = [];

	protected $fillable = [];

    public function retailProductOption(): BelongsTo
    {
        return $this->BelongsTo(RetailProductOption::class, 'no_option', 'no_option');
    }
}
