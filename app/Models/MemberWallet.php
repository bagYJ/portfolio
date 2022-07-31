<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberWallet
 *
 * @property int $no
 * @property string $no_user
 * @property int $no_seq
 * @property string $tr_id
 * @property string|null $cd_card_corp
 * @property int $no_card
 * @property string|null $no_card_user
 * @property string|null $nice_cid
 * @property string|null $card_comp_code
 * @property string|null $card_type
 * @property string|null $offline_use_yn
 * @property string|null $cd_card_regist
 * @property string|null $signature
 * @property string|null $yn_main_card
 * @property string|null $yn_delete
 * @property Carbon|null $dt_del
 * @property Carbon|null $dt_reg
 * @property string|null $cd_pg
 *
 * @package App\Models
 */
class MemberWallet extends Model
{
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no_seq' => 'int',
        'no_card' => 'int'
    ];

    protected $dates = [
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'tr_id',
        'cd_card_corp',
        'no_card',
        'no_card_user',
        'nice_cid',
        'card_comp_code',
        'card_type',
        'offline_use_yn',
        'cd_card_regist',
        'signature',
        'yn_main_card',
        'yn_delete',
        'dt_del',
        'dt_reg',
        'cd_pg'
    ];
}
