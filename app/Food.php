<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
        'name',
        'calorie',
        'red',
        'green',
        'yellow',
        'price',
    ];

    protected $appends = [
        'categories'
    ];

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

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function attachCategory(Category $category)
    {
        if (!$this->categories()->where('category_id', $category->id)->exists()) {
            $this->categories()->attach($category->id);
        }
    }

    public function getCategoriesAttribute()
    {
        return $this->categories()->get();
    }
}
