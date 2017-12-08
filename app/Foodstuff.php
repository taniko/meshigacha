<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foodstuff extends Model
{
    protected $fillable = ['name'];

    protected $visible = ['id', 'name'];

    public function foods()
    {
        return $this->belongsToMany('App\Food');
    }
}
