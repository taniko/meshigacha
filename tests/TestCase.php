<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory;
use App\User;
use App\{
    Allergy,
    Category,
    Food,
    Restaurant
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
        return $food;
    }
}
