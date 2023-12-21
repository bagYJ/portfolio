<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopDetail
 *
 * @property int $no
 * @property int $no_shop
 * @property string|null $ds_image_bg
 * @property string|null $ds_image1
 * @property string|null $ds_image2
 * @property string|null $ds_image3
 * @property string|null $ds_image4
 * @property string|null $ds_image5
 * @property string|null $ds_image6
 * @property string|null $ds_image7
 * @property string|null $ds_image8
 * @property string|null $ds_image9
 * @property string|null $ds_image10
 * @property string|null $ds_priview
 * @property string|null $ds_text1
 * @property string|null $ds_text2
 * @property string|null $ds_text3
 * @property string|null $ds_text4
 * @property string|null $ds_text5
 * @property string|null $ds_text6
 * @property string|null $ds_text7
 * @property string|null $ds_text8
 * @property string|null $ds_text9
 * @property string|null $ds_text10
 * @property string|null $ds_image_pick1
 * @property string|null $ds_image_pick2
 * @property string|null $ds_image_pick3
 * @property string|null $ds_image_pick4
 * @property string|null $ds_image_pick5
 * @property string|null $ds_image_parking
 * @property string|null $yn_open_mon
 * @property string|null $yn_open_tue
 * @property string|null $yn_open_wed
 * @property string|null $yn_open_thu
 * @property string|null $yn_open_fri
 * @property string|null $yn_open_sat
 * @property string|null $yn_open_sun
 * @property string|null $yn_open_rest
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property string|null $nm_shop_franchise
 * @property string|null $nm_owner
 * @property string|null $ds_biz_num
 * @property string|null $ds_franchise_num
 * @property string|null $nm_admin
 * @property string|null $ds_admin_tel
 * @property string|null $nm_sub_adm
 * @property string|null $ds_sub_adm_tel
 * @property string|null $ds_contract_url
 * @property string|null $cd_contract_status
 * @property string|null $cd_pause_type
 * @property string|null $ds_btn_notice
 * @property string|null $yn_self
 * @property string|null $ds_content
 * @property string|null $yn_car_pickup
 * @property string|null $yn_booking_pickup
 * @property string|null $yn_shop_pickup
 *
 * @package App\Models
 */
class ShopDetail extends Model
{
    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = 'dt_del';
    public $incrementing = false;
    public $timestamps = true;
    protected $table = 'shop_detail';
    protected $primaryKey = 'no_shop';

    protected $dates = [
        'dt_upt',
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg',
        'yn_car_pickup',
        'yn_shop_pickup'
    ];
}
