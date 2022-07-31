<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderQrReader
 *
 * @property int $no
 * @property string $no_oil_company
 * @property int $no_shop
 * @property string $ds_display_ark_id
 * @property string $cd_oil_confirm_type
 * @property int|null $no_user
 * @property string|null $no_order
 * @property string|null $ds_uni
 * @property Carbon|null $dt_upt
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class OrderQrReader extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_oil_company',
        'no_shop',
        'ds_display_ark_id',
        'cd_oil_confirm_type',
        'no_user',
        'no_order',
        'ds_uni',
        'dt_upt',
        'dt_reg'
    ];
}
