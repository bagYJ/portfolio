<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class CalculateSendKcp
 *
 * @property int $no
 * @property int $no_shop
 * @property Carbon $dt_reg
 * @property string $cd_bank
 * @property string $ds_bank_acct
 * @property string $nm_acct_name
 * @property float $at_send_price
 * @property string $nm_send_name
 * @property string|null $ds_reply_code
 * @property string|null $ds_reply_msg
 * @property string|null $ds_reply_method
 * @property Carbon|null $dt_reply_date
 * @property string|null $ds_reply_no
 * @property float|null $at_reply_remain_amt
 *
 * @package App\Models
 */
class CalculateSendKcp extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'at_send_price' => 'float',
        'at_reply_remain_amt' => 'float'
    ];

    protected $dates = [
        'dt_reg',
        'dt_reply_date'
    ];

    protected $fillable = [
        'no_shop',
        'dt_reg',
        'cd_bank',
        'ds_bank_acct',
        'nm_acct_name',
        'at_send_price',
        'nm_send_name',
        'ds_reply_code',
        'ds_reply_msg',
        'ds_reply_method',
        'dt_reply_date',
        'ds_reply_no',
        'at_reply_remain_amt'
    ];
}
