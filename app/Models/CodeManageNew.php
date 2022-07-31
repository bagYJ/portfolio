<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class CodeManageNew
 *
 * @property string $id_code_group
 * @property string $id_code
 * @property string $nm_code
 * @property string|null $yn_status
 * @property int|null $no_sort
 * @property string|null $ds_code_detail
 *
 * @package App\Models
 */
class CodeManageNew extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no_sort' => 'int'
    ];

    protected $fillable = [
        'nm_code',
        'yn_status',
        'no_sort',
        'ds_code_detail'
    ];
}
