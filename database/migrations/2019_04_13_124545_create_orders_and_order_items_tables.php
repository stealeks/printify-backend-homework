<?php

use App\Model\Auth\User;
use App\Model\Business\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Model\Business\Order;
use App\Model\Business\OrderItem;

class CreateOrdersAndOrderItemsTables extends Migration
{
    public function up() : void
    {
        Schema::create(Order::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->decimal('sum', 11, 2);

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on(User::TABLE)
                ->onDelete('restrict');

            $table->string('country_code', 2);

            $table->timestamps();
        });

        Schema::create(OrderItem::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('order_id')->unsigned();
            $table->foreign('order_id')
                ->references('id')
                ->on(Order::TABLE)
                ->onDelete('cascade');

            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')
                ->references('id')
                ->on(Product::TABLE)
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(OrderItem::TABLE);
        Schema::dropIfExists(Order::TABLE);
    }
}
