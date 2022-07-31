<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class GitProject
 *
 * @property int $id
 * @property string $project
 * @property array|null $commitidlist
 * @property Carbon $dt_reg
 * @property Carbon $moddt
 *
 * @package App\Models
 */
class GitProject extends Model
{
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'commitidlist' => 'json'
    ];

    protected $dates = [
        'dt_reg',
        'moddt'
    ];

    protected $fillable = [
        'project',
        'commitidlist',
        'dt_reg',
        'moddt'
    ];
}
