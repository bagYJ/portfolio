<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberShopEnterLog
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property int $no_user
 * @property string|null $ds_adver
 * @property int $no_shop
 * @property string|null $yn_is_in
 * @property string|null $no_order
 * @property string|null $ds_dp_ark_id
 * @property string|null $ds_unit_id
 * @property int|null $nt_unit_id_status
 * @property string|null $ds_approval_errcode
 * @property string|null $ds_approval_errmsg
 * @property string|null $id_admin
 *
 * @package App\Models
 */
class MemberShopEnterLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_shop' => 'int',
        'nt_unit_id_status' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg',
        'no_user',
        'ds_adver',
        'no_shop',
        'yn_is_in',
        'no_order',
        'ds_dp_ark_id',
        'ds_unit_id',
        'nt_unit_id_status',
        'ds_approval_errcode',
        'ds_approval_errmsg',
        'id_admin'
    ];
}
