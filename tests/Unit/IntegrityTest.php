<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Food;

class IntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function testIntegrityFoodCommand()
    {
        $restaurant = $this->createRestaurant();
        $restaurant->foods()->save(factory(Food::class)->make());
        $this->createFood();
        $count = Food::count();
        $this->artisan('integrity:foods');
        $this->assertEquals($count - 1, Food::count());
    }
}
