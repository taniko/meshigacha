<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory;
use Illuminate\Http\UploadedFile;
use App\User;
use App\{
    Allergy,
    Category,
    Food,
    Restaurant,
    Photo
};


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * call json api
     * @param  string $method [description]
     * @param  string $uri    [description]
     * @param  array  $params [description]
     * @param  [type] $user   [description]
     * @return [type]         [description]
     */
    public function api(
        string $method,
        string $uri,
        array $params = [],
        User $user = null
    ) {
        $method = strtoupper($method);
        auth()->logout();
        $uri = ltrim($uri, '/');
        $uri = "/api/{$uri}";
        return $this->json($method, $uri, $params);
    }

    public function faker()
    {
        return Factory::create();
    }

    public function createUser() : User
    {
        return factory(User::class)->create();
    }

    public function createRestaurant() : Restaurant
    {
        return factory(Restaurant::class)->create();
    }

    public function createCategory() : Category
    {
        return factory(Category::class)->create();
    }

    public function createAllergy() : Allergy
    {
        return factory(Allergy::class)->create();
    }

    public function createFood(Restaurant $restaurant = null, Category $category = null) : Food
    {
        if (is_null($restaurant)) {
            $restaurant = $this->createRestaurant();
        }
        $food = $restaurant->foods()->save(factory(Food::class)->make());
        if (!is_null($category)) {
            $food->attachCategory($category);
        }
        $file = UploadedFile::fake()->image('dummy.jpg', 100, 100);
        do {
            $hash = str_random(16);
            $filename = "{$hash}.{$file->extension()}";
        } while (Photo::where('filename', $filename)->exists());
        $file->storeAs('public/photos', $filename);
        $food->photos()->save(new Photo(['filename' => $filename]));
        return $food;
    }
}
