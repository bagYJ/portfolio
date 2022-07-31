<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MemberPointcard
 *
 * @property int $no_user
 * @property string $cd_point_cp
 * @property string $id_pointcard
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $yn_agree01
 * @property string|null $yn_agree02
 * @property string|null $yn_agree03
 * @property string|null $yn_agree04
 * @property string|null $yn_agree05
 * @property string|null $yn_agree06
 * @property string|null $yn_agree07
 * @property string|null $yn_delete
 * @property string|null $yn_sale_card
 * @property int|null $no_deal
 *
 * @package App\Models
 */
class MemberPointcard extends Model
{
    protected $primaryKey = 'no_user';
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_deal' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no_user',
        'cd_point_cp',
        'id_pointcard',
        'dt_reg',
        'dt_upt',
        'yn_agree01',
        'yn_agree02',
        'yn_agree03',
        'yn_agree04',
        'yn_agree05',
        'yn_agree06',
        'yn_agree07',
        'yn_delete',
        'yn_sale_card',
        'no_deal'
    ];

    public function promotionDeal(): BelongsTo
    {
        return $this->belongsTo(PromotionDeal::class, 'no_deal', 'no_deal');
    }

    public function gsSaleCard(): BelongsTo
    {
        return $this->belongsTo(GsSaleCard::class, 'id_pointcard', 'id_pointcard');
    }
}
