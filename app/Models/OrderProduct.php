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
 *
 * @package App\Models
 */
class OrderProduct extends Model
{
    use Compoships;

    protected $primaryKey = 'no_order_product';
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'order_product';

    protected $casts
        = [
            'no' => 'int',
            'no_order_product' => 'int',
            'no_product' => 'int',
            'at_price' => 'float',
            'at_price_product' => 'float',
            'at_price_option' => 'float',
            'ct_inven' => 'int',
            'no_sel_group1' => 'int',
            'no_sel_option1' => 'int',
            'no_sel_price1' => 'int',
            'no_sel_group2' => 'int',
            'no_sel_option2' => 'int',
            'no_sel_price2' => 'int',
            'no_sel_group3' => 'int',
            'no_sel_option3' => 'int',
            'no_sel_price3' => 'int',
            'no_sel_group4' => 'int',
            'no_sel_option4' => 'int',
            'no_sel_price4' => 'int',
            'no_sel_group5' => 'int',
            'no_sel_option5' => 'int',
            'no_sel_price5' => 'int',
            'no_event' => 'int'
        ];

    protected $fillable
        = [
            'no',
            'no_order_product',
            'no_order',
            'no_product',
            'nm_product',
            'at_price',
            'at_price_product',
            'at_price_option',
            'ct_inven',
            'no_user',
            'ds_sel_text',
            'no_sel_group1',
            'no_sel_option1',
            'no_sel_price1',
            'no_sel_group2',
            'no_sel_option2',
            'no_sel_price2',
            'no_sel_group3',
            'no_sel_option3',
            'no_sel_price3',
            'no_sel_group4',
            'no_sel_option4',
            'no_sel_price4',
            'no_sel_group5',
            'no_sel_option5',
            'no_sel_price5',
            'no_event',
            'options'
        ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'no_product', 'no_product');
    }

    public function shopProduct(): BelongsTo
    {
        return $this->belongsTo(ShopProduct::class, 'no_product', 'no_product');
    }

    public function washProduct(): BelongsTo
    {
        return $this->belongsTo(WashProduct::class, 'no_product', 'no_product');
    }
}
