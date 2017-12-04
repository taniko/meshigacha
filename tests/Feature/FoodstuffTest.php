<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Foodstuff;

class FoodstuffTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate()
    {
        $data = factory(Foodstuff::class)->make()->toArray();
        $count = Foodstuff::count();
        $response = $this->api('POST', 'foodstuffs', $data);
        $response->assertStatus(200);
        $this->assertEquals($count + 1 , Foodstuff::count());
    }

    public function testSearch()
    {
        for ($i = 0; $i < 10; $i++) {
            factory(Foodstuff::class)->create();
        }
        $response = $this->api('GET', 'foodstuffs');
        $response->assertStatus(200);
        $this->assertEquals(10, count($response->json()));

        $foodstuff = Foodstuff::get()->first();
        $response = $this->api('GET', 'foodstuffs', [
            'name' => $foodstuff->name
        ]);
        $this->assertEquals(1, count($response->json()));
    }

    public function testFind()
    {
        $foodstuff = factory(Foodstuff::class)->create();
        $response = $this->api('GET', "foodstuffs/{$foodstuff->id}");
        $response->assertStatus(200);
        $this->assertEquals($foodstuff->name, ($response->json())['name']);
    }

    public function testFoods()
    {
        for ($i = 0; $i < 2; $i++) {
            $foodstuff = factory(Foodstuff::class)->create();
            for ($j = 0; $j < 5; $j++) {
                $food = $this->createFood();
                $food->attachFoodstuff($foodstuff);
            }
        }
        $allergy = Foodstuff::get()->random();
        $response = $this->api('GET', "foodstuffs/{$foodstuff->id}/foods");
        $response->assertStatus(200);
        $this->assertEquals(5, count($response->json()));
    }
}
