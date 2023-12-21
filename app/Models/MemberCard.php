<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberCard
 * 
 * @property int $no
 * @property int $no_user
 * @property int $no_seq
 * @property string|null $cd_card_corp
 * @property int $no_card
 * @property string|null $no_card_user
 * @property string|null $nm_card
 * @property string|null $ds_pay_passwd
 * @property string $ds_billkey
 * @property string|null $yn_main_card
 * @property string|null $yn_delete
 * @property Carbon|null $dt_del
 * @property Carbon|null $dt_reg
 * @property string|null $cd_pg
 * @property string|null $yn_credit
 *
 * @package App\Models
 */
class MemberCard extends Model
{
	public $incrementing = false;
	public $timestamps = false;

    protected $casts = [
        'no' => 'int',
        'no_user' => 'int',
        'no_seq' => 'int',
        'no_card' => 'int',
        'dt_del' => 'date',
        'dt_reg' => 'date'
    ];

	protected $fillable = [
		'no',
		'cd_card_corp',
		'no_card',
		'no_card_user',
		'nm_card',
		'ds_pay_passwd',
		'ds_billkey',
		'yn_main_card',
		'yn_delete',
		'dt_del',
		'dt_reg',
		'cd_pg',
		'yn_credit'
	];
}
