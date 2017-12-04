<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email'];
    
    public function foods()
    {
        return $this->hasMany('App\Food');
    }
}
