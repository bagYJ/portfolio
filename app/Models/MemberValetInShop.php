<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberValetInShop
 *
 * @property string $ds_adver
 * @property int $no_user
 * @property int $no_shop
 * @property Carbon|null $dt_enter_date
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberValetInShop extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_enter_date',
        'dt_reg'
    ];

    protected $fillable = [
        'dt_enter_date',
        'dt_reg'
    ];
}
