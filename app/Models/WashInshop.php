<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class WashInshop
 *
 * @property int $no_shop
 * @property int $no_shop_in
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class WashInshop extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'no_shop_in' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg'
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'no_shop', 'no_shop');
    }
}
