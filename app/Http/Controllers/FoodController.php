<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\Food\{
    CreateRequest,
    SearchRequest,
    GachaRequest
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
        $food = DB::transaction(function () use ($request, $restaurant) {
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

            $this->savePhotos($food, $request);
            return $food;
        });
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

    /**
     * save new photos for food
     * @param  App\Food                             $food    [description]
     * @param  App\Http\Requests\Food\CreateRequest $request [description]
     * @throws Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function savePhotos(Food $food, CreateRequest $request)
    {
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
    }

    /**
     * gacha
     * @param  App\Http\Requests\Food\GachaRequest $request
     * @return App\Food
     */
    public function gacha(GachaRequest $request)
    {
        $ids = Restaurant::withDistance($request->input('lat'), $request->input('lng'))
            ->orderBy('distance', 'desc')
            ->get()
            ->where('distance', '<=', $request->input('distance', 1000))
            ->filter(function ($restaurant) {
                return !is_null($restaurant->distance);
            })->pluck('id');
        $query = Food::whereIn('restaurant_id', $ids)
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
        return $query->with('restaurant')->inRandomOrder()->first();
    }
}
