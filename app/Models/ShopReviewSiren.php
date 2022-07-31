<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopReviewSiren
 *
 * @property int $no
 * @property int|null $no_shop
 * @property int|null $no_user
 * @property int|null $no_review
 * @property string|null $ds_userip
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ShopReviewSiren extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'no_user' => 'int',
        'no_review' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'no_user',
        'no_review',
        'ds_userip',
        'dt_reg'
    ];
}
