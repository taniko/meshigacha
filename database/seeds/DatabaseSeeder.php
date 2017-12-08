<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AllergySeeder::class);
        $this->call(RestaurantSeeder::class);
        $this->call(CategorySeeder::class);
    }
}
