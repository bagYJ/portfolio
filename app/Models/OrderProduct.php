<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class OrderProduct
 *
 * @property int $no
 * @property int $no_order_product
 * @property string $no_order
 * @property int $no_product
 * @property float $at_price
 * @property float $at_price_product
 * @property float $at_price_option
 * @property int $ct_inven
 * @property int $no_user
 * @property string|null $ds_sel_text
 * @property int|null $no_sel_group1
 * @property int|null $no_sel_option1
 * @property int|null $no_sel_price1
 * @property int|null $no_sel_group2
 * @property int|null $no_sel_option2
 * @property int|null $no_sel_price2
 * @property int|null $no_sel_group3
 * @property int|null $no_sel_option3
 * @property int|null $no_sel_price3
 * @property int|null $no_sel_group4
 * @property int|null $no_sel_option4
 * @property int|null $no_sel_price4
 * @property int|null $no_sel_group5
 * @property int|null $no_sel_option5
 * @property int|null $no_sel_price5
 * @property int|null $no_event
 * @property Product $product
 * @property ShopProduct $shopProduct
 *
 * @package App\Models
 */
class OrderProduct extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'no_order_product';
    protected $table = 'order_product';

    protected $casts = [];

    protected $fillable = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'no_product', 'no_product');
    }

    public function shopProduct(): BelongsTo
    {
        return $this->belongsTo(ShopProduct::class, 'no_product', 'no_product');
    }

}
