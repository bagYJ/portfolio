<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MainTitle
 *
 * @property string $cd_biz_kind
 * @property string $text
 * @property string $app_route
 * @property string $yn_use
 * @property string $id_reg
 * @property Carbon $dt_reg
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class MainTitle extends Model
{
    protected $table = 'main_title';
    protected $primaryKey = 'cd_biz_kind';
    public $incrementing = false;
    public $timestamps = false;

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'text',
        'app_route',
        'yn_use',
        'id_reg',
        'dt_reg',
        'id_upt',
        'dt_upt'
    ];
}
