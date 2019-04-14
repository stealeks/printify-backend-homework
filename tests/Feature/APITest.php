<?php

namespace Tests\Feature;

use App\Model\Auth\User;
use TestDatabaseSeeder;
use Tests\TestCase;

abstract class APITest extends TestCase
{
    /**
     * @var User|null
     */
    private $user = null;

    /**
     * @return User
     */
    protected function getTestUser() : User
    {
        if (!$this->user) {
            $this->user = User::where('token', '=', TestDatabaseSeeder::USER_TOKEN)->first();
        }

        return $this->user;
    }

    /**
     * @return User
     */
    protected function login() : User
    {
        $user = $this->getTestUser();

        $this->actingAs($user);

        return $user;
    }
}
