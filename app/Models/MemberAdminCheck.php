<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\EnumYN;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class MemberAdminCheck
 *
 * @property int $no
 * @property int $no_user
 * @property string|null $ds_content
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class MemberAdminCheck extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no_user',
        'ds_content',
        'id_reg',
        'dt_reg',
        'id_upt',
        'dt_upt'
    ];

    public function aptList(): HasOne
    {
        return $this->hasOne(AptList::class, 'id_apt', 'id_apt')
            ->where('yn_status', EnumYN::Y->name);
    }
}
