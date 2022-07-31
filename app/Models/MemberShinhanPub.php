<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class MemberShinhanPub
 *
 * @property int|null $no_seq
 * @property string|null $nm_user
 * @property string|null $ds_ci
 *
 * @package App\Models
 */
class MemberShinhanPub extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no_seq' => 'int'
    ];

    protected $fillable = [
        'no_seq',
        'nm_user',
        'ds_ci'
    ];
}
