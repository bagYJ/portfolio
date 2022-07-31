<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class BbsAppNotice
 *
 * @property int $no
 * @property string|null $id_admin
 * @property string|null $ds_title
 * @property string|null $ds_content
 * @property string|null $yn_show
 * @property Carbon|null $dt_reg
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $cd_service
 * @property string|null $ds_popup_thumb
 * @property string|null $yn_popup
 * @property string|null $yn_prior_popup
 *
 * @package App\Models
 */
class BbsAppNotice extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'id_admin',
        'ds_title',
        'ds_content',
        'yn_show',
        'dt_reg',
        'id_upt',
        'dt_upt',
        'cd_service',
        'ds_popup_thumb',
        'yn_popup',
        'yn_prior_popup'
    ];
}
