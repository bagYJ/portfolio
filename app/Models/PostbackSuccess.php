<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PostbackSuccess
 *
 * @property int $no
 * @property string|null $batch_key
 * @property string|null $batch_kind
 * @property string|null $no_order
 * @property string|null $no_user
 * @property Carbon|null $dt_day
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class PostbackSuccess extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;


    protected $dates = [
        'dt_day',
        'dt_reg'
    ];

    protected $fillable = [
        'batch_key',
        'batch_kind',
        'no_order',
        'no_user',
        'dt_day',
        'dt_reg'
    ];
}
