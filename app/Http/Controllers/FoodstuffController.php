<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Foodstuff;
use App\Http\Requests\Foodstuff\{
    CreateRequest,
    SearchRequest
};

class FoodstuffController extends Controller
{
    public function create(CreateRequest $request) {
        return Foodstuff::create($request->input());
    }

    public function search(SearchRequest $request)
    {
        $name = $request->input('name');
        $query = Foodstuff::query();
        if ($request->has('name')) {
            $query = $query->where('name', $request->input('name'));
        }
        return $query->get();
    }

    public function find(Request $request, Foodstuff $foodstuff)
    {
        return $foodstuff;
    }
}
