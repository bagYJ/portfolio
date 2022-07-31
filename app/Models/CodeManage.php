<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class CodeManage
 *
 * @property int $no
 * @property int|null $no_group
 * @property int $no_code
 * @property string|null $nm_code
 * @property string|null $yn_status
 * @property string|null $ds_etc_value
 *
 * @package App\Models
 */
class CodeManage extends Model
{
    protected $primaryKey = 'no_code';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no' => 'int',
        'no_group' => 'int',
        'no_code' => 'int'
    ];

    protected $fillable = [
        'no',
        'no_group',
        'nm_code',
        'yn_status',
        'ds_etc_value'
    ];
}
