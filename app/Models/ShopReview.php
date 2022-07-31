<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ShopReview
 *
 * @property int $no
 * @property int|null $no_shop
 * @property string|null $no_order
 * @property int|null $no_user
 * @property string|null $nm_nick
 * @property string|null $cd_review_auther
 * @property float|null $at_grade
 * @property string|null $ds_content
 * @property string|null $yn_status
 * @property string|null $ds_userip
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ShopReview extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'no_user' => 'int',
        'at_grade' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'no_order',
        'no_user',
        'nm_nick',
        'cd_review_auther',
        'at_grade',
        'ds_content',
        'yn_status',
        'ds_userip',
        'dt_reg'
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'no_shop');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'no_user')->select(['ds_phone', 'id_user']);
    }
}
