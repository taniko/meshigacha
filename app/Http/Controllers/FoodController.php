<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Food\{
    CreateRequest,
    SearchRequest
};

use App\{
    Category,
    Food,
    Restaurant
};

class FoodController extends Controller
{
    public function create(CreateRequest $request, Restaurant $restaurant)
    {
        $food = $restaurant->foods()->save(new Food($request->input()));
        if ($request->has('category')) {
            $category = Category::firstOrCreate(['name' => $request->input('category')]);
            $food->attachCategory($category);
        }
        return $food;
    }

    public function searchInRestaurant(SearchRequest $request, Restaurant $restaurant)
    {
        return $restaurant->foods()->get();
    }
}
