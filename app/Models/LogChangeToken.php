<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class LogChangeToken
 *
 * @property int $no
 * @property int $no_user
 * @property string|null $ds_token_before
 * @property string|null $ds_token_after
 * @property Carbon|null $dt_upt
 * @property string|null $cd_service
 *
 * @package App\Models
 */
class LogChangeToken extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = null;
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;


    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_upt'
    ];

    protected $fillable = [
        'no_user',
        'ds_token_before',
        'ds_token_after',
        'dt_upt',
        'cd_service'
    ];
}
