<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DB;

class Restaurant extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email'];

    protected $geofields = 'positions';

    protected $visible = [
        'id',
        'name',
        'address',
        'phone',
        'email',
        'positions'
    ];

    public function foods()
    {
        return $this->hasMany('App\Food');
    }

    public function setPositionsAttribute(array $values)
    {
        $this->attributes['positions'] = DB::raw("(GeomFromText('POINT({$values['lat']} {$values['lng']})'))");
    }

    public function getPositionsAttribute($value)
    {
        if (isset($value) && preg_match('/POINT\(([0-9.]+)\s([0-9.]+)\)/i', $value, $m) == 1) {
            $result = [
                'latitude'  => $m[1],
                'longitude' => $m[2],
            ];
        } else {
            $result = [
                'latitude'  => null,
                'longitude' => null,
            ];
        }
        return $result;
    }

    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)->addSelect(
            '*',
            DB::raw("astext({$this->geofields}) as {$this->geofields}")
        );
    }
}
