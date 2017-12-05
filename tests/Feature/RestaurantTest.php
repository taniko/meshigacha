<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Restaurant;

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
}
