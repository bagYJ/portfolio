<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class DirectOrderList
 *
 * @property int $no
 * @property int $no_user
 * @property string $cd_biz_kind
 * @property string $no_order
 * @property int $ct_order //todo 추후 추가 예정
 * @property Carbon $dt_reg
 *
 * @package App\Models
 */
class DirectOrderList extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $casts = [
        'no_user' => 'int',
        'ct_order' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'cd_biz_kind',
        'no_order',
        'ct_order',
        'dt_reg'
    ];

    public function orderList(): HasOne
    {
        return $this->hasOne(OrderList::class, 'no_order', 'no_order');
    }

    public function parkingOrderList(): HasOne
    {
        return $this->hasOne(ParkingOrderList::class, 'no_order', 'no_order');
    }
}
