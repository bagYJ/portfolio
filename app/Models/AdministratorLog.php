<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdministratorLog
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property string $id_admin
 * @property string $ds_log
 * @property string|null $ds_menu_1
 * @property string|null $ds_menu_2
 * @property string|null $ds_menu_3
 * @property string|null $no_company
 * @property string|null $no_partner
 * @property string|null $no_shop
 * @property string|null $mode
 * @property string|null $ds_ip
 *
 * @package App\Models
 */
class AdministratorLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg',
        'id_admin',
        'ds_log',
        'ds_menu_1',
        'ds_menu_2',
        'ds_menu_3',
        'no_company',
        'no_partner',
        'no_shop',
        'mode',
        'ds_ip'
    ];
}
