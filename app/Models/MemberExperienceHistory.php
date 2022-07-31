<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberExperienceHistory
 *
 * @property int $no
 * @property int $no_experience
 * @property string|null $id_admin
 * @property string|null $ds_content
 * @property Carbon $dt_reg
 *
 * @package App\Models
 */
class MemberExperienceHistory extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_experience' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_experience',
        'id_admin',
        'ds_content',
        'dt_reg'
    ];
}
