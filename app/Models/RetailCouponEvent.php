<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class RetailCouponEvent
 *
 * @property int $no
 * @property string $nm_event
 * @property string $cd_calculate_main
 * @property int $at_disct_money
 * @property int|null $at_min_price
 * @property int|null $at_expire_day
 * @property string|null $yn_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property string|null $cd_third_party
 *
 * @package App\Models
 */
class RetailCouponEvent extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'at_disct_money' => 'int',
        'at_min_price' => 'int',
        'at_expire_day' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'nm_event',
        'cd_calculate_main',
        'at_disct_money',
        'at_min_price',
        'at_expire_day',
        'yn_status',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg',
        'cd_third_party'
    ];

    public function retailCouponEventUsepartner(): HasMany
    {
        return $this->hasMany(RetailCouponEventUsepartner::class, 'no_event', 'no')->where('yn_status', 'Y');
    }
}
