<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class OpnetOilCode
 *
 * @property int $seq
 * @property string $code_type
 * @property string|null $sub_type
 * @property string $code_key
 * @property string $code_name
 * @property string|null $parent_code
 *
 * @package App\Models
 */
class OpnetOilCode extends Model
{
    protected $primaryKey = 'seq';
    public $timestamps = false;

    protected $fillable = [
        'code_type',
        'sub_type',
        'code_key',
        'code_name',
        'parent_code'
    ];
}
