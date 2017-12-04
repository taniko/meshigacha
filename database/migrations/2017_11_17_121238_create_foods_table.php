<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->float('calorie')->unsigned();
            $table->float('red')->unsigned();
            $table->float('green')->unsigned();
            $table->float('yellow')->unsigned();
            $table->integer('restaurant_id')->unsigned();
            $table->integer('price')->unsigned();

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
        Schema::table('foods', function (Blueprint $table) {
            $table->dropForeign('foods_restaurant_id_foreign');
        });
        Schema::dropIfExists('foods');
    }
}
