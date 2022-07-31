<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PromotionPin
 *
 * @property int $no
 * @property string $no_pin
 * @property int|null $no_deal
 * @property int|null $no_user
 * @property string $ds_cpn_no
 * @property string|null $cd_deal_status
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class PromotionPin extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_deal' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_pin',
        'no_deal',
        'no_user',
        'ds_cpn_no',
        'cd_deal_status',
        'dt_reg'
    ];
}
