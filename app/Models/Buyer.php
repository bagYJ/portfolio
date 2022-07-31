<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Buyer
 *
 * @property string $id_buyer
 * @property string $nm_buyer
 * @property string $ds_pwd
 * @property Carbon|null $dt_reg
 * @property string|null $ds_company
 *
 * @package App\Models
 */
class Buyer extends Model
{
    protected $primaryKey = 'id_buyer';
    public $incrementing = false;
    public $timestamps = true;


    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'nm_buyer',
        'ds_pwd',
        'dt_reg',
        'ds_company'
    ];
}
