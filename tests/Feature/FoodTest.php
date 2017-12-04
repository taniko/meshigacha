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
}
