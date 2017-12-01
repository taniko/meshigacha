<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodRestaurantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_restaurant', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('food_id')->unsigned();
            $table->integer('restaurant_id')->unsigned();

            $table->foreign('food_id')
                ->references('id')->on('foods')
                ->onDelete('cascade');
            $table->foreign('restaurant_id')
                ->references('id')->on('restaurants')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_restaurant', function (Blueprint $table) {
            $table->dropForeign('food_restaurant_restaurant_id_foreign');
            $table->dropForeign('food_restaurant_food_id_foreign');
        });
        Schema::dropIfExists('food_restaurant');
    }
}
