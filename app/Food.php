<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    public function allergies()
    {
        return $this->belongsToMany('App\Allergy');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }

    public function foodstuffs()
    {
        return $this->belongsToMany('App\Foodstuff');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
