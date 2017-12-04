<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Category\{
    CreateRequest,
    SearchRequest
};
use App\Allergy;

class AllergyController extends Controller
{
    public function create(CreateRequest $request)
    {
        return Allergy::create($request->input());
    }

    public function search(SearchRequest $request)
    {
        $query = Allergy::query();
        if ($request->has('name')) {
            $query = $query->where('name', $request->input('name'));
        }
        return $query->get();
    }
}
