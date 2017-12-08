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
    Restaurant,
    Photo
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

        // save photos
        foreach ($request->file('photos') as $file) {
            do {
                $hash = str_random(16);
                $filename = "{$hash}.{$file->extension()}";
            } while (Photo::where('filename', $filename)->exists());
            $file->storeAs('public/photos', $filename);
            $food->photos()->save(new Photo(['filename' => $filename]));

        }
        return $food;
    }

    public function search(SearchRequest $request, Restaurant $restaurant = null)
    {
        if (is_null($restaurant)) {
            $query = Food::query();
        } else {
            $query = $restaurant->foods();
        }
        return $query->get();
    }
}
