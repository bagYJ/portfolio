<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class GitProjectPublish
 *
 * @property int $id
 * @property string $project
 * @property string $commitid
 * @property array $filepath
 * @property string $type
 * @property Carbon $dt_reg
 *
 * @package App\Models
 */
class GitProjectPublish extends Model
{
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'filepath' => 'json'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'project',
        'commitid',
        'filepath',
        'type',
        'dt_reg'
    ];
}
