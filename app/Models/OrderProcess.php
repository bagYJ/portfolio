<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class OrderProcess
 *
 * @property int $no
 * @property string $no_order
 * @property int $no_user
 * @property int $no_shop
 * @property string|null $cd_order_process
 * @property Carbon|null $dt_order_process
 *
 * @package App\Models
 */
class OrderProcess extends Model
{
    use Compoships;

    public $timestamps = false;

    protected $casts = [
        'no_user' => 'int',
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_order_process'
    ];

    protected $fillable = [
        'no_user',
        'no_order',
        'no_shop',
        'cd_order_process',
        'dt_order_process'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderList::class, 'no_order', 'no_order');
    }
}
