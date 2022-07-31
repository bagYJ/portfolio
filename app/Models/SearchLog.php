<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class SearchLog
 *
 * @property int $no
 * @property int|null $no_user
 * @property int|null $no_shop
 * @property string|null $search_word
 * @property string|null $ref_week
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class SearchLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_shop',
        'search_word',
        'ref_week',
        'dt_reg'
    ];
}
