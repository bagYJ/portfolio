<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderListWithdrawal
 *
 * @property int $no
 * @property string $no_order
 * @property int $no_user
 * @property string|null $id_user
 * @property string|null $nm_user
 * @property string|null $ds_phone
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class OrderListWithdrawal extends Model
{
    protected $primaryKey = 'no_order';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'no_user',
        'id_user',
        'nm_user',
        'ds_phone',
        'dt_reg'
    ];
}
