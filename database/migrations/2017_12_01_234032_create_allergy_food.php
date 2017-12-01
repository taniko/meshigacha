<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllergyFood extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allergy_food', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('allergy_id')->unsigned();
            $table->integer('food_id')->unsigned();

            $table->foreign('allergy_id')
                ->references('id')->on('allergies')
                ->onDelete('cascade');
            $table->foreign('food_id')
                ->references('id')->on('foods')
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
        Schema::table('allergy_food', function (Blueprint $table) {
            $table->dropForeign('allergy_food_allergy_id_foreign');
            $table->dropForeign('allergy_food_food_id_foreign');
        });
        Schema::dropIfExists('allergy_food');
    }
}
