<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Category\{
    CreateRequest,
    SearchRequest
};
use App\Category;

class CategoryController extends Controller
{
    public function create(CreateRequest $request)
    {
        return Category::create($request->input());
    }

    public function search(SearchRequest $request)
    {
        $query = Category::query();
        if ($request->has('name')) {
            $query = $query->where('name', $request->input('name'));
        }
        return $query->get();
    }

    public function foods(Request $request, Category $category)
    {
        return $category->foods()->get();
    }
}
