<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderQrReaderLog
 *
 * @property int $no
 * @property string $no_oil_company
 * @property int $no_shop
 * @property string $ds_display_ark_id
 * @property string $cd_oil_confirm_type
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class OrderQrReaderLog extends Model
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
        'no_oil_company',
        'no_shop',
        'ds_display_ark_id',
        'cd_oil_confirm_type',
        'dt_reg'
    ];
}
