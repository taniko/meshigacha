<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate()
    {
        $data = factory(Category::class)->make()->toArray();
        $count = Category::count();
        $response = $this->api('POST', 'categories', $data);
        $response->assertStatus(200);
        $this->assertEquals($count + 1 , Category::count());
    }

    public function testSearch()
    {
        for ($i = 0; $i < 10; $i++) {
            factory(Category::class)->create();
        }
        $category = Category::get()->first();
        $response = $this->api('GET', 'categories', ['name' => $category->name]);
        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json()));
    }

    public function testFind()
    {
        $category = factory(Category::class)->create();
        $response = $this->api('GET', "categories/{$category->id}");
        $response->assertStatus(200);
        $this->assertEquals($category->name, ($response->json())['name']);
    }

    public function testGetFoods()
    {
        $restaurant = $this->createRestaurant();

        for ($i = 0; $i < 2; $i++) {
            $category = $this->createCategory();
            for ($j = 0; $j < 5; $j++) {
                $this->createFood($restaurant, $category);
            }
        }

        $category = Category::get()->random();
        $response = $this->api('GET', "categories/{$category->id}/foods");
        $response->assertStatus(200);
        $this->assertEquals(5, count($response->json()));
    }
}
