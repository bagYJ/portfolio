<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RetailCouponEventUsepartner
 *
 * @property int $no
 * @property int $no_event
 * @property int $no_partner
 * @property string|null $yn_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class RetailCouponEventUsepartner extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'no_event' => 'int',
        'no_partner' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_event',
        'no_partner',
        'yn_status',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'no_partner', 'no_partner');
    }
}
