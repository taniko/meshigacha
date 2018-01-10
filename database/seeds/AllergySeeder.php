<?php

use Illuminate\Database\Seeder;
use App\Allergy;

class AllergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = database_path('file/allergies.txt');
        foreach (explode("\n", file_get_contents($file)) as $name) {
            if (mb_strlen($name) === 0) {
                continue;
            }
            Allergy::firstOrCreate(['name' => $name]);
        }
    }
}
