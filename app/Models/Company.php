<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Company
 *
 * @property int $no_company
 * @property string $nm_company
 * @property string $yn_inc
 * @property string|null $ds_comp_tel
 * @property string|null $ds_comp_admin
 * @property string|null $ds_tel
 * @property string|null $ds_email
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class Company extends Model
{
    protected $primaryKey = 'no_company';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_company' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'nm_company',
        'yn_inc',
        'ds_comp_tel',
        'ds_comp_admin',
        'ds_tel',
        'ds_email',
        'dt_reg',
        'dt_upt'
    ];
}
