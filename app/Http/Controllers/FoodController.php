<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Food\{
    CreateRequest,
    SearchRequest
};

use App\{
    Food,
    Restaurant
};

class FoodController extends Controller
{
    public function create(CreateRequest $request, Restaurant $restaurant)
    {
        $food = new Food($request->input());
        return $restaurant->foods()->save($food);
    }

    public function searchInRestaurant(SearchRequest $request, Restaurant $restaurant)
    {
        return $restaurant->foods()->get();
    }
}
