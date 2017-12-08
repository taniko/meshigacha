<?php

use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;
use App\Restaurant;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = database_path('file/restaurants.yml');
        foreach (Yaml::parse(file_get_contents($file)) as $data) {
            Restaurant::firstOrCreate([
                'name'      => $data['name'],
                'address'   => $data['address'],
                'phone'     => $data['phone'],
                'email'     => $data['email'],
            ]);
        }
    }
}
