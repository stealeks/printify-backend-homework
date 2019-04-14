<?php

use App\Model\Auth\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Model\Business\Product;
use App\Model\Business\Classificators;

class CreateProductsTable extends Migration
{
    public function up() : void
    {
        Schema::create(Product::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->decimal('price', 11, 2);

            $table->bigInteger('type_id')->unsigned();
            $table->foreign('type_id')
                ->references('id')
                ->on(Classificators\Type::TABLE)
                ->onDelete('restrict');


            $table->bigInteger('color_id')->unsigned();
            $table->foreign('color_id')
                ->references('id')
                ->on(Classificators\Color::TABLE)
                ->onDelete('restrict');


            $table->bigInteger('size_id')->unsigned();
            $table->foreign('size_id')
                ->references('id')
                ->on(Classificators\Size::TABLE)
                ->onDelete('restrict');

            $table->unique(['size_id', 'color_id', 'type_id']);

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on(User::TABLE)
                ->onDelete('restrict');

            $table->string('title');

            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(Product::TABLE);
    }
}
