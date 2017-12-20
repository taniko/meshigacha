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
            $restaurant = Restaurant::firstOrCreate([
                'name'      => $data['name'],
                'address'   => $data['address'],
                'phone'     => $data['phone'],
                'email'     => $data['email'],
            ]);
            if (is_null($restaurant->positions['latitude']) || is_null($restaurant->positions['longitude'])) {
                $restaurant->positions = [
                    'lat' => $data['lat'],
                    'lng' => $data['lng'],
                ];
                $restaurant->save();
            }
        }
    }
}
