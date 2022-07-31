<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MemberRetailCouponUsepartner
 *
 * @property int $no
 * @property int|null $no_user
 * @property string|null $no_coupon
 * @property string|null $cd_cpn_condi_type
 * @property string|null $ds_target
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberRetailCouponUsepartner extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_coupon',
        'cd_cpn_condi_type',
        'ds_target',
        'dt_reg'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'ds_target', 'no_partner');
    }
}
