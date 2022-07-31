<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class MemberWashCouponUseshop
 *
 * @property int $no
 * @property int|null $no_user
 * @property string|null $no_event
 * @property string|null $cd_cpn_condi_type
 * @property string|null $ds_target
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberWashCouponUseshop extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_event',
        'cd_cpn_condi_type',
        'ds_target',
        'dt_reg'
    ];

    public function partner(): HasOne
    {
        return $this->hasOne(Partner::class, 'no_partner', 'ds_target');
    }

    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class, 'no_shop', 'ds_target');
    }
}
