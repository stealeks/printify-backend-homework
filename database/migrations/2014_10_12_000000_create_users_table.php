<?php

use App\Model\Auth\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up() : void
    {
        Schema::create(User::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');
            $table->string('token', 60)->unique()->nullable();
            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(User::TABLE);
    }
}
