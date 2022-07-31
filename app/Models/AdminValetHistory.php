<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdminValetHistory
 *
 * @property int $no
 * @property string $id_admin
 * @property int $no_shop
 * @property Carbon $dt_reg
 *
 * @package App\Models
 */
class AdminValetHistory extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'id_admin',
        'no_shop',
        'dt_reg'
    ];
}
