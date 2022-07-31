<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class CouponList
 *
 * @property int $no_event
 * @property string $ds_coupon_no
 * @property int|null $no_user
 *
 * @package App\Models
 */
class CouponList extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no_event' => 'int',
        'no_user' => 'int'
    ];

    protected $fillable = [
        'no_user'
    ];
}
