<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodFoodstuffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_foodstuff', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('food_id')->unsigned();
            $table->integer('foodstuff_id')->unsigned();

            $table->foreign('food_id')
                ->references('id')->on('foods')
                ->onDelete('cascade');
            $table->foreign('foodstuff_id')
                ->references('id')->on('foodstuffs')
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
        Schema::table('food_foodstuff', function (Blueprint $table) {
            $table->dropForeign('food_foodstuff_food_id_foreign');
            $table->dropForeign('food_foodstuff_foodstuff_id_foreign');
        });
        Schema::dropIfExists('food_foodstuff');
    }
}
