<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberEvent
 *
 * @property int $no
 * @property int $no_seq
 * @property int $no_user
 * @property string|null $ds_ci
 * @property string|null $ds_join_path
 * @property Carbon|null $dt_join_reg
 * @property string|null $ds_cpn_no
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberEvent extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_seq' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_join_reg',
        'dt_reg'
    ];

    protected $fillable = [
        'no_seq',
        'no_user',
        'ds_ci',
        'ds_join_path',
        'dt_join_reg',
        'ds_cpn_no',
        'dt_reg'
    ];
}
