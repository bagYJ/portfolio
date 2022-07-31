<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PartnerHistory
 *
 * @property int $no
 * @property int $no_partner
 * @property string|null $yn_status
 * @property string|null $cd_contract_status
 * @property Carbon|null $dt_reg
 * @property string $id_admin
 *
 * @package App\Models
 */
class PartnerHistory extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;


    protected $casts = [
        'no_partner' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_partner',
        'yn_status',
        'cd_contract_status',
        'dt_reg',
        'id_admin'
    ];
}
