<?php

use App\Model\Business\Classificators;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductClassificatorsTables extends Migration
{
    public function up() : void
    {
        Schema::create(Classificators\Type::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title')->unique();
        });

        Schema::create(Classificators\Color::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');

            // #000000-#ffffff
            $table->unsignedMediumInteger('value')->unique();

            $table->string('title')->unique();
        });

        Schema::create(Classificators\Size::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title')->unique();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(Classificators\Type::TABLE);
        Schema::dropIfExists(Classificators\Color::TABLE);
        Schema::dropIfExists(Classificators\Size::TABLE);
    }
}
