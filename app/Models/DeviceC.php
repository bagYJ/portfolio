<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class DeviceC
 *
 * @property int $no
 * @property string $ds_sn
 * @property string|null $id_admin
 * @property int|null $no_user
 * @property string|null $cd_device
 * @property string|null $cd_qna_type
 * @property string|null $ds_title
 * @property string|null $ds_content
 * @property string|null $id_answer
 * @property string|null $ds_answer
 * @property Carbon|null $dt_answer
 * @property string|null $ds_userip
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class DeviceC extends Model
{
    protected $primaryKey = 'no';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_answer',
        'dt_reg'
    ];

    protected $fillable = [
        'ds_sn',
        'id_admin',
        'no_user',
        'cd_device',
        'cd_qna_type',
        'ds_title',
        'ds_content',
        'id_answer',
        'ds_answer',
        'dt_answer',
        'ds_userip',
        'dt_reg'
    ];
}
