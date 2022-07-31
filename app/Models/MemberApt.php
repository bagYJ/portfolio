<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class MemberApt
 *
 * @property int $no
 * @property string $id_apt
 * @property int $no_user
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberApt extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'id_apt',
        'no_user',
        'dt_reg'
    ];

    public function aptList(): HasOne
    {
        return $this->hasOne(AptList::class, 'id_apt', 'id_apt');
    }
}
