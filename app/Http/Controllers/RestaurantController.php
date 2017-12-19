<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Restaurant\{
    CreateRequest,
    GachaRequest,
    UpdateRequest
};
use App\Restaurant;

class RestaurantController extends Controller
{
    public function create(CreateRequest $request) {
        return Restaurant::create($request->input());
    }

    public function index(Request $request)
    {
        return Restaurant::get();
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
        return $query->inRandomOrder()->first();
    }

    public function update(UpdateRequest $request, Restaurant $restaurant)
    {
        $restaurant->update($request->all());
        return $restaurant;
    }
}
