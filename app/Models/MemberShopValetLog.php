<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberShopValetLog
 *
 * @property int $no
 * @property int|null $no_valet
 * @property Carbon $dt_reg
 * @property string|null $id_admin
 * @property int|null $no_user
 * @property int|null $no_shop
 * @property string|null $cd_valet_order_status
 * @property string|null $no_order
 * @property string|null $cd_payment_valet
 * @property string|null $yn_on_site_pay
 *
 * @package App\Models
 */
class MemberShopValetLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;


    protected $casts = [
        'no_valet' => 'int',
        'no_user' => 'int',
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_valet',
        'dt_reg',
        'id_admin',
        'no_user',
        'no_shop',
        'cd_valet_order_status',
        'no_order',
        'cd_payment_valet',
        'yn_on_site_pay'
    ];
}
