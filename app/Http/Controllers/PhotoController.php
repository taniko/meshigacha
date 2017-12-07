<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function show(Request $request, string $filename)
    {
        $path = storage_path("app/public/photos/{$filename}");
        if (\File::exists($path)) {
            return response()->file($path);
        } else {
            abort(404);
        }
    }
}
