<?php

use Illuminate\Database\Seeder;
use App\Model\Auth\User;
use Illuminate\Support\Facades\Hash;

class TestDatabaseSeeder extends Seeder
{
    public const USER_TOKEN = 'customMadeToken';
    public const USER_PASSWORD = '100%ValidPassword';

    public function run() : void
    {
        $this->createTestUser();
    }

    private function createTestUser() : void
    {
        $user = User::create([
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => Hash::make(self::USER_PASSWORD),
        ]);

        // Since it is not fillable
        $user->token = self::USER_TOKEN;

        $user->save();
    }
}
