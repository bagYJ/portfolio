<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MemberDeal
 *
 * @property int $no
 * @property int $no_user
 * @property string $no_pin
 * @property int $no_deal
 * @property string $yn_pointcard_issue
 * @property Carbon|null $dt_pointcard_reg
 * @property string|null $ds_pointcard_reg_msg
 * @property Carbon|null $dt_deal_use_end
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberDeal extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_deal' => 'int'
    ];

    protected $dates = [
        'dt_pointcard_reg',
        'dt_deal_use_end',
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_pin',
        'no_deal',
        'yn_pointcard_issue',
        'dt_pointcard_reg',
        'ds_pointcard_reg_msg',
        'dt_deal_use_end',
        'dt_reg'
    ];

    public function promotionDeal(): BelongsTo
    {
        return $this->belongsTo(PromotionDeal::class, 'no_deal', 'no_deal');
    }
}
