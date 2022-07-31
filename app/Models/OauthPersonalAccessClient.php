<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OauthPersonalAccessClient
 *
 * @property int $id
 * @property string $client_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class OauthPersonalAccessClient extends Model
{

    protected $fillable = [
        'client_id'
    ];
}
