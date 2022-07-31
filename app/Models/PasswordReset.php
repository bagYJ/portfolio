<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PasswordReset
 *
 * @property string $email
 * @property string $token
 * @property Carbon|null $created_at
 *
 * @package App\Models
 */
class PasswordReset extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $hidden = [
        'token'
    ];

    protected $fillable = [
        'email',
        'token'
    ];
}
