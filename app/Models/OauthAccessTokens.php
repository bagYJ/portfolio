<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OauthAccessToken
 *
 * @property string $id
 * @property int|null $user_id
 * @property string $client_id
 * @property string|null $name
 * @property string|null $scopes
 * @property bool $revoked
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $expires_at
 *
 * @package App\Models
 */
class OauthAccessTokens extends Model
{
    public $incrementing = false;

    protected $casts = [
        'user_id' => 'int',
        'revoked' => 'bool'
    ];

    protected $dates = [
        'expires_at'
    ];

    protected $fillable = [
        'user_id',
        'client_id',
        'name',
        'scopes',
        'revoked',
        'expires_at'
    ];
}
