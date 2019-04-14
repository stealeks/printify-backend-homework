<?php

namespace Tests\Feature\Http\Controllers\API\Auth;

use Tests\Feature\APITest;

class UserControllerTest extends APITest
{
    public function testSuccessfulUserDetails() : void
    {
        $user = $this->login();

        $response = $this->getJson('/api/auth/user');

        $response->assertSuccessful();

        $array = $user->jsonSerialize();

        $response->assertExactJson($array);
    }
}
