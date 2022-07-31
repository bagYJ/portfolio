<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class BbsQna
 *
 * @property int $no
 * @property int|null $no_user
 * @property string|null $cd_question
 * @property string|null $ds_title
 * @property string|null $ds_content
 * @property string|null $ds_userip
 * @property string|null $id_answer
 * @property Carbon|null $dt_answer
 * @property Carbon|null $dt_reg
 * @property string|null $ds_answer_content
 * @property string|null $cd_service
 *
 * @package App\Models
 */
class BbsQna extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_answer',
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'cd_question',
        'ds_title',
        'ds_content',
        'ds_userip',
        'id_answer',
        'dt_answer',
        'dt_reg',
        'ds_answer_content',
        'cd_service'
    ];
}
