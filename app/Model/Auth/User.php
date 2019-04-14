<?php

namespace App\Model\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $token
 */
final class User extends Authenticatable
{
    public const TABLE = 'users';

    /**
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateToken() : void
    {
        do {
            $token = Str::random(60);

            $isTokenNonUnique = $this->where('token', $token)->exists();
        } while ($isTokenNonUnique);

        $this->token = $token;

        $this->save();
    }
}
