<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class OrderListDelivery
 *
 * @property string $no_order
 * @property string|null $nm_user
 * @property string|null $ds_phone
 * @property string|null $ds_post_num
 * @property string|null $ds_addr
 * @property string|null $ds_pwd
 * @property float|null $at_delivery_price
 * @property string|null $ds_invoice_num
 * @property string|null $ds_comment
 *
 * @package App\Models
 */
class OrderListDelivery extends Model
{
    protected $primaryKey = 'no_order';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'at_delivery_price' => 'float'
    ];

    protected $fillable = [
        'nm_user',
        'ds_phone',
        'ds_post_num',
        'ds_addr',
        'ds_pwd',
        'at_delivery_price',
        'ds_invoice_num',
        'ds_comment'
    ];
}
