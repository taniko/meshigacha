<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\{
    Food,
    Restaurant
};

class FoodTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate()
    {
        $restaurant = factory(Restaurant::class)->create();
        $data = factory(Food::class)->make()->toArray();
        $count = Food::count();

        $response = $this->api('POST', "restaurants/{$restaurant->id}/foods", $data);
        $response->assertStatus(200);
        $this->assertEquals($count + 1 , Food::count());
        $this->assertEquals(1 , $restaurant->foods()->count());

        $response = $this->api('GET', "restaurants/{$restaurant->id}/foods");
        $response->assertStatus(200);
        $this->assertEquals(1 , count($response->json()));
    }

    public function testCreateWithCategory()
    {
        $restaurant = $this->createRestaurant();
        $data = factory(Food::class)->make()->toArray();
        $data['category'] = $this->faker()->words(1, true);
        $response = $this->api('POST', "restaurants/{$restaurant->id}/foods", $data);
        $response->assertStatus(200);
    }

    public function testCreateWithAllergies()
    {
        $restaurant = $this->createRestaurant();
        $data = factory(Food::class)->make()->toArray();
        $data['allergies'] = $this->faker()->words(3);
        $response = $this->api('POST', "restaurants/{$restaurant->id}/foods", $data);
        $response->assertStatus(200);
        $food = Food::orderBy('id', 'desc')->first();
        $this->assertEquals(3, $food->allergies()->count());
    }
}
