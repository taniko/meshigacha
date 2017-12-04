<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Food\{
    CreateRequest,
    SearchRequest
};

use App\{
    Allergy,
    Category,
    Food,
    Foodstuff,
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
        if ($request->has('allergies')) {
            foreach ($request->input('allergies') as $name) {
                $allergy = Allergy::firstOrCreate(['name' => $name]);
                $food->attachAllergy($allergy);
            }
        }

        // attach foodstuffs
        if ($request->has('foodstuffs')) {
            foreach ($request->input('foodstuffs') as $name) {
                $foodstuff = Foodstuff::firstOrCreate(['name' => $name]);
                $food->attachFoodstuff($foodstuff);
            }
        }
        
        return $food;
    }

    public function searchInRestaurant(SearchRequest $request, Restaurant $restaurant)
    {
        return $restaurant->foods()->get();
    }
}
