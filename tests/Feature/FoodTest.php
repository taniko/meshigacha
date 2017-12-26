<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use App\{
    Food,
    Restaurant,
    Allergy
};

class FoodTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown()
    {
        $storage = Storage::disk('public');
        $files = $storage->files('photos');
        $storage->deleteDirectory('photos');
        parent::tearDown();
    }

    public function testCreate()
    {
        $restaurant = factory(Restaurant::class)->create();
        $data = factory(Food::class)->make()->toArray();
        $data['photos'][] = UploadedFile::fake()->image('dummy.jpg', 100, 100);
        $count = Food::count();

        $response = $this->api('POST', "restaurants/{$restaurant->id}/foods", $data);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals($count + 1 , Food::count());
        $this->assertEquals(1 , $restaurant->foods()->count());
        $this->assertEquals(1, (Food::find($data['id']))->photos()->count());

        $response = $this->api('GET', "restaurants/{$restaurant->id}/foods");
        $response->assertStatus(200);
        $this->assertEquals(1 , count($response->json()));

        $response = $this->call('GET', "");
        $this->assertEquals(200, $response->status());
    }

    public function testCreateByBase64Photo()
    {
        $restaurant = factory(Restaurant::class)->create();
        $data = factory(Food::class)->make()->toArray();
        $file = UploadedFile::fake()->image('dummy.png', 100, 100);
        $data['base64_photos'][] = base64_encode(file_get_contents($file->path()));
        $count = Food::count();

        $response = $this->api('POST', "restaurants/{$restaurant->id}/foods", $data);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals($count + 1 , Food::count());
        $this->assertEquals(1 , $restaurant->foods()->count());
        $this->assertEquals(1, (Food::find($data['id']))->photos()->count());

        $response = $this->api('GET', "restaurants/{$restaurant->id}/foods");
        $response->assertStatus(200);
        $this->assertEquals(1 , count($response->json()));

        $response = $this->call('GET', "");
        $this->assertEquals(200, $response->status());
    }

    public function testCreateWithCategory()
    {
        $restaurant = $this->createRestaurant();
        $data = factory(Food::class)->make()->toArray();
        $data['photos'][] = UploadedFile::fake()->image('dummy.jpg', 100, 100);
        $data['category'] = $this->faker()->words(1, true);

        $response = $this->api('POST', "restaurants/{$restaurant->id}/foods", $data);
        $response->assertStatus(200);
    }

    public function testCreateWithAllergies()
    {
        $restaurant = $this->createRestaurant();
        $data = factory(Food::class)->make()->toArray();
        $data['photos'][] = UploadedFile::fake()->image('dummy.jpg', 100, 100);
        $data['allergies'] = $this->faker()->words(3);

        $response = $this->api('POST', "restaurants/{$restaurant->id}/foods", $data);
        $response->assertStatus(200);
        $food = Food::orderBy('id', 'desc')->first();
        $this->assertEquals(3, $food->allergies()->count());
    }

    public function testCreateWithFoodstuffs()
    {
        $restaurant = $this->createRestaurant();
        $data = factory(Food::class)->make()->toArray();
        $data['photos'][] = UploadedFile::fake()->image('dummy.jpg', 100, 100);
        $data['foodstuffs'] = $this->faker()->words(3);

        $response = $this->api('POST', "restaurants/{$restaurant->id}/foods", $data);
        $response->assertStatus(200);
        $food = Food::orderBy('id', 'desc')->first();
        $this->assertEquals(3, $food->foodstuffs()->count());
    }

    public function testGacha()
    {
        $this->artisan('db:seed');
        $restaurants = Restaurant::get();
        $allergies = Allergy::get();
        $restaurants->each(function($restaurant, $key) use ($allergies) {
            $food = $this->createFood($restaurant);
            $food->attachAllergy($allergies[$key]);
        });
        $response = $this->api('GET', 'foods/gacha', [
            'lat' => 34.979482,
            'lng' => 135.964019,
            'distance' => 100,
        ]);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals('リンクスクエア', $data['restaurant']['name']);

        $response = $this->api('GET', 'foods/gacha', [
            'lat'           => 34.979482,
            'lng'           => 135.964019,
            'distance'      => 1000,
            'uncontained'   => [$data['allergies'][0]['name']],
        ]);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals('ユニオンスクエア', $data['restaurant']['name']);
    }
}
