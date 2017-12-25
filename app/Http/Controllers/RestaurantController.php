<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Restaurant\{
    CreateRequest,
    GachaRequest,
    UpdateRequest,
    SearchRequest
};
use App\Restaurant;

class RestaurantController extends Controller
{
    public function create(CreateRequest $request) {
        return Restaurant::create($request->input());
    }

    public function index(SearchRequest $request)
    {
        $query = Restaurant::query();
        if($request->has('lat') && $request->has('lng')) {
            $query->withDistance($request->input('lat'), $request->input('lng'));
        }
        $restaurants = $query->get();
        if ($request->has('lat') && $request->has('lng')) {
            $restaurants = $restaurants->where('distance', '<=', $request->input('distance', 1000));
        }
        return $restaurants;
    }

    public function find(Request $request, Restaurant $restaurant)
    {
        return $restaurant;
    }

    public function gacha(GachaRequest $request, Restaurant $restaurant)
    {
        $query = $restaurant->foods()
            ->if($request->has('min_price'), function ($query) use ($request) {
                return $query->where('price', '>=', $request->input('min_price'));
            })->if($request->has('max_price'), function ($query) use ($request) {
                return $query->where('price', '<=', $request->input('max_price'));
            });
        if ($request->has('uncontained')) {
            $names = $request->input('uncontained');
            $query = $query->whereDoesntHave('allergies', function ($q) use ($names) {
                $q->whereIn('allergies.name', $names);
            });
        }
        if ($request->has('categories')) {
            $names = $request->input('categories');
            $query = $query->whereHas('categories', function ($q) use ($names) {
                $q->whereIn('categories.name', $names);
            });
        }
        if ($request->has('foodstuffs')) {
            $names = $request->input('foodstuffs');
            $query = $query->whereHas('foodstuffs', function ($q) use ($names) {
                $q->whereIn('foodstuffs.name', $names);
            });
        }
        return $query->inRandomOrder()->first();
    }

    public function update(UpdateRequest $request, Restaurant $restaurant)
    {
        $restaurant->update($request->all());
        return $restaurant;
    }
}
