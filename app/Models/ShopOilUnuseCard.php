<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopOilUnuseCard
 *
 * @property int $seq
 * @property int $no_shop
 * @property string $cd_card_corp
 * @property string $nm_card_corp_show
 * @property string $yn_unuse_status
 * @property string $unuse_reason
 * @property Carbon $dt_reg
 * @property string $id_reg
 * @property Carbon|null $dt_upt
 * @property string|null $id_upt
 *
 * @package App\Models
 */
class ShopOilUnuseCard extends Model
{
    protected $primaryKey = 'seq';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no_shop',
        'cd_card_corp',
        'nm_card_corp_show',
        'yn_unuse_status',
        'unuse_reason',
        'dt_reg',
        'id_reg',
        'dt_upt',
        'id_upt'
    ];
}
