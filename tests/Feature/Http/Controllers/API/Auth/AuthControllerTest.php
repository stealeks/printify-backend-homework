<?php

namespace Tests\Feature\Http\Controllers\API\Auth;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use TestDatabaseSeeder;
use Tests\Feature\APITest;

class AuthControllerTest extends APITest
{
    public function testSuccessfulRegister() : void
    {
        $response = $this->requestRegister([
            'name' => 'Aleksandrs',
            'email' => 'test@aleksandr.st',
            'password' => 'SuperSecretPassword3000!'
        ]);

        $response->assertJsonStructure([
            'token',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $token = $response->json('token');

        $this->assertEquals(60, mb_strlen($token));
    }

    public function testGirlHasNoNameRegister()
    {
        $response = $this->requestRegister([
            'email' => 'game_of_thrones@example.com',
            'password' => 'Valar Morghulis'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors('name');
    }

    public function testNonUniqueEmailRegister() : void
    {
        $user = $this->getTestUser();

        $response = $this->requestRegister([
            'name' => 'Alex',
            'email' => $user->email,
            'password' => 'ThisIsValidPasswordIHope'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors('email');
    }

    public function testShortPasswordRegister()
    {
        $response = $this->requestRegister([
            'name' => 'name',
            'email' => 'email@example.com',
            'password' => 'pass'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors('password');
    }

    public function testSuccessfulLogin()
    {
        $user = $this->getTestUser();

        $response = $this->requestLogin([
            'email' => $user->email,
            'password' => TestDatabaseSeeder::USER_PASSWORD,
        ]);

        $response->assertSuccessful();

        $response->assertJsonFragment([
            'token' => TestDatabaseSeeder::USER_TOKEN,
        ]);
    }

    public function testWrongPasswordLogin()
    {
        $user = $this->getTestUser();

        $response = $this->requestLogin([
            'email' => $user->email,
            'password' => TestDatabaseSeeder::USER_PASSWORD.'1',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonFragment([
            'message' => 'Bad Request',
        ]);
    }

    public function testWithoutPasswordLogin()
    {
        $response = $this->requestLogin([
            'email' => 'email@example.com',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors('password');
    }

    public function testWithoutEmailLogin()
    {
        $response = $this->requestLogin([
            'password' => 'password1',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors('email');
    }

    public function testInvalidEmailLogin()
    {
        $response = $this->requestLogin([
            'email' => 'email',
            'password' => 'password1',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors('email');
    }

    public function testFailedLogin()
    {
        $response = $this->requestLogin([
            'email' => 'valid@example.com',
            'password' => 'ValidPassword1',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonFragment([
            'message' => 'Bad Request',
        ]);
    }

    public function testAuthenticationGuardSuccessful() : void
    {
        $user = $this->getTestUser();

        $response = $this->requestLogin([
            'email' => $user->email,
            'password' => TestDatabaseSeeder::USER_PASSWORD,
        ]);

        $token = $response->json('token');

        $response = $this->getJson('/api/auth/user', [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertSuccessful();
    }

    public function testAuthenticationGuardFail() : void
    {
        $user = $this->getTestUser();

        $response = $this->requestLogin([
            'email' => $user->email,
            'password' => TestDatabaseSeeder::USER_PASSWORD,
        ]);

        $token = $response->json('token');

        $response = $this->getJson('/api/auth/user', [
            'X-Auth' => $token,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response->assertJsonFragment([
            'message' => 'Unauthenticated.',
        ]);
    }

    /**
     * @param mixed[] $data
     * @return TestResponse
     */
    private function requestRegister(array $data) : TestResponse
    {
        return $this->postJson('/api/auth/register', $data);
    }

    /**
     * @param mixed[] $data
     * @return TestResponse
     */
    private function requestLogin(array $data) : TestResponse
    {
        return $this->postJson('/api/auth/login', $data);
    }
}
