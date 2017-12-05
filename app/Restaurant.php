<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Restaurant extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email'];

    public function foods()
    {
        return $this->hasMany('App\Food');
    }

    public function gacha(Request $request)
    {
        return $this->foods()->inRandomOrder()->first();
    }
}
