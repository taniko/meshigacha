<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Food\{
    CreateRequest,
    SearchRequest
};
use Storage;
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
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                do {
                    $hash = str_random(16);
                    $filename = "{$hash}.{$file->extension()}";
                } while (Photo::where('filename', $filename)->exists());
                $file->storeAs('public/photos', $filename);
                $food->photos()->save(new Photo(['filename' => $filename]));

            }
        } elseif ($request->has('base64_photos')) {
            $f = finfo_open();
            $v = \Validator::make([], []);
            $types = [
                'image/jpeg'    => 'jpg',
                'image/png'     => 'png',
            ];
            foreach ($request->input('base64_photos') as $key => $data) {
                $data       = base64_decode($data);
                $mime_type  = finfo_buffer($f, $data, FILEINFO_MIME_TYPE);
                if (!isset($types[$mime_type])) {
                    $v->errors()->add("base64_photos.{$key}", 'Not allowed image type');
                    continue;
                }
                $ext = $types[$mime_type];
                do {
                    $hash = str_random(16);
                    $filename = "{$hash}.{$ext}";
                } while (Photo::where('filename', $filename)->exists());
                Storage::disk('public')->put("photos/{$filename}", $data);
                $food->photos()->save(new Photo(['filename' => $filename]));
            }
            if (count($v->errors()->all()) > 0) {
                return $v->errors();
            }
        } else {
            logger()->error('Photos do not exist', $request->all());
            abort(422);
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
