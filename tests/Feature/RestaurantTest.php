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
        $response = $this->api('POST', 'restaurants' , $data);
        $response->assertStatus(200);
        $this->assertEquals($count + 1 , Restaurant::count());
        print_r($response->json());
    }
}
