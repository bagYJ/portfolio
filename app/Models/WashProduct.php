<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class WashProduct
 *
 * @property int $no
 * @property int $no_product
 * @property int $no_shop
 * @property string|null $nm_product
 * @property float|null $at_price
 * @property string|null $cd_car_kind
 * @property string|null $yn_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class WashProduct extends Model
{
    use Compoships;

    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_product' => 'int',
        'no_shop' => 'int',
        'at_price' => 'float'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_product',
        'no_shop',
        'nm_product',
        'at_price',
        'cd_car_kind',
        'yn_status',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg'
    ];

    public function washOptions(): HasMany
    {
        return $this->hasMany(WashOption::class, ['no_product', 'no_shop'], ['no_product', 'no_shop']);
    }
}
