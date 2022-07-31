<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class CccaInquire
 *
 * @property int $no
 * @property string|null $ds_company
 * @property string|null $ds_name
 * @property string|null $ds_position
 * @property string|null $ds_tel
 * @property string|null $ds_email
 * @property string|null $yn_email_pub_status
 * @property string|null $ds_inquire
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $yn_ccca
 *
 * @package App\Models
 */
class CccaInquire extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'ds_company',
        'ds_name',
        'ds_position',
        'ds_tel',
        'ds_email',
        'yn_email_pub_status',
        'ds_inquire',
        'dt_reg',
        'dt_upt',
        'yn_ccca'
    ];
}
