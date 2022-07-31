<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class Temp
 *
 * @property string|null $no_user
 * @property string|null $cpn_no
 *
 * @package App\Models
 */
class Temp extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'no_user',
        'cpn_no'
    ];
}
