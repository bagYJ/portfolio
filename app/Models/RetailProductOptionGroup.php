<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class RetailProductOptionGroup
 *
 * @property int $no
 * @property int $no_group
 * @property int|null $no_partner
 * @property int|null $no_product
 * @property string|null $nm_group
 * @property string|null $cd_option_type
 * @property int|null $at_select_min
 * @property int|null $at_select_max
 * @property string|null $ds_status
 * @property int|null $at_view
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class RetailProductOptionGroup extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no_group' => 'int',
        'no_partner' => 'int',
        'no_product' => 'int',
        'at_select_min' => 'int',
        'at_select_max' => 'int',
        'at_view' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'no_group',
        'no_partner',
        'no_product',
        'nm_group',
        'cd_option_type',
        'at_select_min',
        'at_select_max',
        'ds_status',
        'at_view',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg'
    ];

    public function retailProduct(): BelongsTo
    {
        return $this->belongsTo(RetailProduct::class, 'no_product', 'no_product');
    }

    public function productOptionProducts(): HasMany
    {
        return $this->hasMany(RetailProductOption::class, 'no_group', 'no_group');
    }
}
