<?php

use Illuminate\Database\Seeder;
use App\Foodstuff;

class FoodstuffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = database_path('file/foodstuffs.txt');
        foreach (explode("\n", file_get_contents($file)) as $name) {
            if (mb_strlen($name) === 0) {
                continue;
            }
            Foodstuff::firstOrCreate(['name' => $name]);
        }
    }
}
