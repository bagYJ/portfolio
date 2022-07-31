<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\Contracts\HasAbilities;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\Sanctum;

trait HasApiTokens
{
    use \Laravel\Sanctum\HasApiTokens;
    /**
     * The access token the user is using for the current request.
     *
     * @var HasAbilities
     */
    protected $accessToken;

    /**
     * Get the access tokens that belong to model.
     *
     * @return MorphMany
     */
    public function tokens()
    {
        return $this->morphMany(Sanctum::$personalAccessTokenModel, 'tokenable');
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param  string  $ability
     * @return bool
     */
    public function tokenCan(string $ability)
    {
        return $this->accessToken && $this->accessToken->can($ability);
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param string $name
     * @param int $noUser
     * @param array $abilities
     * @return NewAccessToken
     */
    public function createToken(string $name, int $noUser, array $abilities = ['*']): NewAccessToken
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => $plainTextToken = hash('sha256', @implode('_', [
                'ds_project' => 'owinrsm',
                'no_user' => $noUser,
                'ds_access_rand_rsm' => now() . mt_rand(100, 999),
            ])),
            'abilities' => $abilities,
        ]);

        return new NewAccessToken($token, $plainTextToken);
    }

    /**
     * Get the access token currently associated with the user.
     *
     * @return HasAbilities
     */
    public function currentAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the current access token for the user.
     *
     * @param  HasAbilities  $accessToken
     * @return \Laravel\Sanctum\HasApiTokens
     */
    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}