<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ConfigImage
 *
 * @property int $no
 * @property string|null $ds_main_imgpath
 * @property Carbon|null $dt_start
 * @property Carbon|null $dt_end
 * @property string|null $yn_booking
 * @property string|null $ds_comment
 * @property string|null $id_admin
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ConfigImage extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $dates = [
        'dt_start',
        'dt_end',
        'dt_reg'
    ];

    protected $fillable = [
        'ds_main_imgpath',
        'dt_start',
        'dt_end',
        'yn_booking',
        'ds_comment',
        'id_admin',
        'dt_reg'
    ];
}
