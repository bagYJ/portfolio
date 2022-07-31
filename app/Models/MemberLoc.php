<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class MemberLoc
 *
 * @property int|null $no
 * @property string|null $no_user
 * @property string|null $lat
 * @property string|null $lng
 * @property string|null $dt_reg
 *
 * @package App\Models
 */
class MemberLoc extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int'
    ];

    protected $fillable = [
        'no',
        'no_user',
        'lat',
        'lng',
        'dt_reg'
    ];
}
