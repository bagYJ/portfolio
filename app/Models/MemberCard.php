<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property string $img_card
 *
 * @package App\Models
 */
class MemberCard extends Model
{
    use Compoships;
    use SoftDeletes;

    protected $primaryKey = 'no_card';
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = 'dt_del';

    protected $casts = [
        'no' => 'int',
        'no_user' => 'int',
        'no_seq' => 'int',
        'no_card' => 'int'
    ];

    protected $dates = [
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'no_user',
        'no_seq',
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

//    protected static function booted()
//    {
//        static::addGlobalScope('yn_delete_n', function (Builder $builder) {
//            $builder->where('yn_delete', 'N');
//        });
//    }
}
