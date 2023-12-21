<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProductOption
 *
 * @property int $no
 * @property int|null $no_partner
 * @property int|null $no_group
 * @property int $no_option
 * @property string|null $nm_option
 * @property float|null $at_add_price
 * @property int|null $ct_order
 * @property string $yn_cup_deposit
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property string|null $cd_option_status
 *
 * @package App\Models
 */
class ProductOption extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = 'dt_del';
    public $incrementing = false;
    public $timestamps = true;
    protected $primaryKey = 'no_option';
    protected $casts = [
        'no' => 'int',
        'no_partner' => 'int',
        'no_group' => 'int',
        'no_option' => 'int',
        'at_add_price' => 'float',
        'ct_order' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'no_partner',
        'no_group',
        'nm_option',
        'at_add_price',
        'ct_order',
        'yn_cup_deposit',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg',
        'cd_option_status',
        'cd_spc',
        'yn_check_stock'
    ];

    public function partner(): belongsTo
    {
        return $this->belongsTo(Partner::class, 'no_partner');
    }

    public function group(): belongsTo
    {
        return $this->belongsTo(ProductOptionGroup::class, 'no_group');
    }

    public function productOptionIgnore(): HasMany
    {
        return $this->hasMany(ProductOptionIgnore::class, 'no_option', 'no_option');
    }
}
