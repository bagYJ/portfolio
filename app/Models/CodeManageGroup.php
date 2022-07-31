<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class CodeManageGroup
 *
 * @property int $no
 * @property string $no_group
 * @property string|null $nm_field
 * @property string|null $nm_group
 *
 * @package App\Models
 */
class CodeManageGroup extends Model
{
    protected $primaryKey = 'no_group';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no' => 'int'
    ];

    protected $fillable = [
        'no',
        'nm_field',
        'nm_group'
    ];
}
