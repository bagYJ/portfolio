<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\AppType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * Class RetailProduct
 *
 * @property int $no
 * @property int $no_product
 * @property int $no_partner
 * @property int|null $no_category
 * @property int|null $no_sub_category
 * @property string|null $nm_product
 * @property string|null $ds_content
 * @property float|null $at_price_before
 * @property float|null $at_price
 * @property Carbon|null $dt_sale_st
 * @property Carbon|null $dt_sale_end
 * @property string|null $ds_image_path
 * @property string|null $ds_detail_image_path
 * @property string|null $no_barcode
 * @property string|null $cd_discount_sale
 * @property string|null $yn_option
 * @property string|null $yn_new
 * @property string|null $yn_vote
 * @property string|null $yn_show
 * @property int|null $at_view
 * @property string|null $ds_status
 * @property string|null $ds_avn_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property int|null $cnt_product
 * @property string $yn_soldout
 *
 * @package App\Models
 */
class RetailProduct extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = 'dt_del';

    protected $casts = [
        'no_product' => 'int',
        'no_partner' => 'int',
        'no_category' => 'int',
        'no_sub_category' => 'int',
        'at_price_before' => 'float',
        'at_price' => 'float',
        'at_view' => 'int'
    ];

    protected $dates = [
        'dt_sale_st',
        'dt_sale_end',
        'dt_upt',
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'no_product',
        'no_partner',
        'no_category',
        'no_sub_category',
        'nm_product',
        'ds_content',
        'at_price_before',
        'at_price',
        'dt_sale_st',
        'dt_sale_end',
        'ds_image_path',
        'ds_detail_image_path',
        'no_barcode',
        'cd_discount_sale',
        'yn_option',
        'yn_new',
        'yn_vote',
        'yn_show',
        'at_view',
        'ds_status',
        'ds_avn_status',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg'
    ];

    protected static function booted()
    {
        static::addGlobalScope('status_y', function (Builder $builder) {
            $builder->where([
                (getAppType() == AppType::AVN ? 'ds_avn_status' : 'ds_status') => 'Y',
                'yn_show' => 'Y'
            ])->whereBetween(DB::raw('now()'), [DB::raw('dt_sale_st'), DB::raw('dt_sale_end')]);
        });
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'no_partner', 'no_partner');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RetailCategory::class, 'no_category', 'no_category');
    }

    public function productOptionGroups(): HasMany
    {
        return $this->hasMany(RetailProductOptionGroup::class, 'no_product', 'no_product');
    }
}
