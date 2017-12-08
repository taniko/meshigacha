<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Restaurant extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email'];

    protected $visible = [
        'id',
        'name',
        'address',
        'phone',
        'email',
    ];

    public function foods()
    {
        return $this->hasMany('App\Food');
    }
}
