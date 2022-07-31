<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Carbon\Carbon;

/**
 * Class WashOption
 *
 * @property int $no
 * @property int $no_option
 * @property int $no_shop
 * @property int $no_product
 * @property string|null $nm_option
 * @property float|null $at_price
 * @property string|null $yn_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class WashOption extends Model
{
    use Compoships;

    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_option' => 'int',
        'no_shop' => 'int',
        'no_product' => 'int',
        'at_price' => 'float'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_option',
        'no_shop',
        'no_product',
        'nm_option',
        'at_price',
        'yn_status',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg'
    ];

    public function product()
    {
        return $this->belongsTo(ProductOption::class, 'no_product', 'no_product');
    }
}
