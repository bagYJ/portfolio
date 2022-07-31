<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class TollMemberUse
 *
 * @property string $ds_trn_owin
 * @property string $ds_trn_key
 * @property string|null $ds_trn_date
 * @property string|null $ds_trn_time
 * @property string|null $ds_car_number
 * @property float|null $at_price
 * @property string|null $ds_car_pass_date
 * @property string|null $ds_car_pass_time
 * @property string|null $ds_up_down
 * @property string|null $ds_collect_chn
 * @property string|null $ds_tunnel
 * @property string|null $ds_car_type
 * @property string|null $ds_res_code
 * @property string|null $ds_res_msg
 * @property string|null $ds_etc
 * @property int|null $no_user
 *
 * @package App\Models
 */
class TollMemberUse extends Model
{
    protected $primaryKey = 'ds_trn_owin';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'at_price' => 'float',
        'no_user' => 'int'
    ];

    protected $fillable = [
        'ds_trn_key',
        'ds_trn_date',
        'ds_trn_time',
        'ds_car_number',
        'at_price',
        'ds_car_pass_date',
        'ds_car_pass_time',
        'ds_up_down',
        'ds_collect_chn',
        'ds_tunnel',
        'ds_car_type',
        'ds_res_code',
        'ds_res_msg',
        'ds_etc',
        'no_user'
    ];
}
