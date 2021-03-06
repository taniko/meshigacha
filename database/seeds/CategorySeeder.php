<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = database_path('file/categories.txt');
        foreach (explode("\n", file_get_contents($file)) as $name) {
            if (mb_strlen($name) === 0) {
                continue;
            }
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
