<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class RetailProductBestLabel
 *
 * @property int $no
 * @property int $no_partner
 * @property string $ds_label
 * @property string|null $ds_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class RetailProductBestLabel extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_partner' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_partner',
        'ds_label',
        'ds_status',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg'
    ];
}
