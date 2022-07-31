<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdBannerItemShowLog
 *
 * @property int $seq
 * @property int $no_banner_area
 * @property int $no_banner_item
 * @property int $no_user
 * @property string $action_type
 * @property Carbon $dt_action
 *
 * @package App\Models
 */
class AdBannerItemShowLog extends Model
{
    protected $primaryKey = 'seq';
    public $timestamps = false;

    protected $casts = [
        'no_banner_area' => 'int',
        'no_banner_item' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_action'
    ];

    protected $fillable = [
        'no_banner_area',
        'no_banner_item',
        'no_user',
        'action_type',
        'dt_action'
    ];
}
