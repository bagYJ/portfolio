<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderAdminCheck
 *
 * @property int $no
 * @property int $no_partner
 * @property int $no_shop
 * @property int $no_user
 * @property string $no_order
 * @property string|null $cd_order_adm_chk
 * @property string|null $ys_is_in
 * @property string|null $ds_adver
 * @property string|null $ds_dp_ark_id
 * @property string|null $ds_dp_ark_id2
 * @property string|null $ds_unit_id
 * @property string|null $ds_unit_id2
 * @property string|null $ds_content
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class OrderAdminCheck extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_partner' => 'int',
        'no_shop' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_partner',
        'no_shop',
        'no_user',
        'no_order',
        'cd_order_adm_chk',
        'ys_is_in',
        'ds_adver',
        'ds_dp_ark_id',
        'ds_dp_ark_id2',
        'ds_unit_id',
        'ds_unit_id2',
        'ds_content',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg'
    ];
}
