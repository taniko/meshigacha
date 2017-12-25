<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\{
    Food,
    Restaurant
};

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate()
    {
        $data = factory(Restaurant::class)->make()->toArray();
        $count = Restaurant::count();
        $response = $this->api('POST', 'restaurants', $data);
        $response->assertStatus(200);
        $this->assertEquals($count + 1 , Restaurant::count());
    }

    public function testIndex()
    {
        for ($i = 0; $i < 10; $i++) {
            factory(Restaurant::class)->create();
        }
        $response = $this->api('GET', 'restaurants');
        $response->assertStatus(200);
        $this->assertEquals(10, count($response->json()));
    }

    public function testSearchRestaurant()
    {
        $this->artisan('db:seed');
        $response = $this->api('GET', 'restaurants', [
            'lat' => 34.979482,
            'lng' => 135.964019,
            'distance' => 100,
        ]);
        $this->assertEquals(1, count($response->json()));
    }

    public function testFind()
    {
        $restaurant = factory(Restaurant::class)->create();
        $response = $this->api('GET', "restaurants/{$restaurant->id}");
        $response->assertStatus(200);
        $this->assertEquals($restaurant->name, ($response->json())['name']);
    }

    public function testGacha()
    {
        $restaurant = $this->createRestaurant();
        $foods = [];
        for ($i = 0; $i < 10; $i++) {
            $foods[] = $this->createFood($restaurant);
        }
        $response = $this->api('GET', "restaurants/{$restaurant->id}/gacha");
        $response->assertStatus(200);
        $data = $response->json();
        $foods = array_filter($foods, function ($food) use ($data) {
            return $food->id === $data['id'];
        });
        $this->assertEquals(1, count($foods));
    }

    public function testGachaPrice()
    {
        $restaurant = $this->createRestaurant();
        for ($i = 0; $i < 2; $i++) {
            $this->createFood($restaurant);
        }
        $foods = Food::orderBy('price', 'asc')->get();

        // min_price
        $response = $this->api('GET', "restaurants/{$restaurant->id}/gacha", [
            'min_price' => $foods[0]->price + 1,
        ]);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals($foods[1]->id, ($response->json())['id']);

        // max_price
        $response = $this->api('GET', "restaurants/{$restaurant->id}/gacha", [
            'max_price' => $foods[1]->price - 1,
        ]);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals($foods[0]->id, ($response->json())['id']);
    }

    public function testGachaAllergy()
    {
        $restaurant = $this->createRestaurant();
        for ($i = 0; $i < 2; $i++) {
            $this->createFood($restaurant);
        }
        $foods   = Food::get();
        $allergy = $this->createAllergy();
        $foods[0]->attachAllergy($allergy);
        $response = $this->api('GET', "restaurants/{$restaurant->id}/gacha", [
            'uncontained' => [$allergy->name],
        ]);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals($foods[1]->id, ($response->json())['id']);
    }

    public function testGachaCategory()
    {
        $restaurant = $this->createRestaurant();
        for ($i = 0; $i < 2; $i++) {
            $this->createFood($restaurant);
        }
        $foods    = Food::get();
        $category = $this->createCategory();
        $foods[0]->attachCategory($category);
        $response = $this->api('GET', "restaurants/{$restaurant->id}/gacha", [
            'categories' => [$category->name],
        ]);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals($foods[0]->id, $data['id']);
        $this->assertEquals($category->name, $data['categories'][0]['name']);
    }

    public function testGachaFoodstuff()
    {
        $restaurant = $this->createRestaurant();
        for ($i = 0; $i < 2; $i++) {
            $this->createFood($restaurant);
        }
        $food      = Food::all()->random();
        $foodstuff = $this->createFoodstuff();
        $food->attachFoodstuff($foodstuff);
        $response = $this->api('GET', "restaurants/{$restaurant->id}/gacha", [
            'foodstuffs' => [$foodstuff->name],
        ]);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals($food->id, $data['id']);
        $this->assertEquals($foodstuff->name, $data['foodstuffs'][0]['name']);
    }



    public function testUpdate()
    {
        $restaurant = $this->createRestaurant();
        $data = ['name' => 'foobar'];
        $response = $this->api('PATCH', "restaurants/{$restaurant->id}", $data);
        $response->assertStatus(200);
        $restaurant = Restaurant::find($restaurant->id);
        $this->assertEquals($data['name'], $restaurant->name);
    }

    public function testConvertAddress()
    {
        $address = '滋賀県草津市野路東１丁目１−１';
        $geo = Restaurant::a2p($address);
        $this->assertInternalType('double', $geo['lat']);
        $this->assertInternalType('double', $geo['lng']);
    }
}
