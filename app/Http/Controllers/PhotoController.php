<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;

class PhotoController extends Controller
{
    public function show(Request $request, string $filename)
    {
        $path = storage_path("app/public/photos/{$filename}");
        if (Photo::where('filename', $filename)->exists() && \File::exists($path)) {
            return response()->file($path);
        } else {
            abort(404);
        }
    }
}
