<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

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
        'positions',
        'distance',
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

    public static function a2p(string $address) : array
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://maps.googleapis.com/maps/api/geocode/json', [
                'query' => [
                    'address' => $address,
                    'key'     => getenv('GOOGLE_MAPS_KEY'),
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            $result = [
                'lat'   => $data['results'][0]['geometry']['location']['lat'],
                'lng'   => $data['results'][0]['geometry']['location']['lng'],
            ];
        } catch (\Exception $e) {
            logger()->error('failed get geometry', ['address' => $address]);
            $result = ['lat' => null, 'lng' => null];
        }
        return $result;
    }

    public function scopeWithDistance($query, float $latitude, float $longitude)
    {
        return $query
            ->addSelect(DB::raw('ST_Distance(POINT(?, ?), `positions`) * 111 *1000 as `distance`'))
            ->setBindings([$latitude, $longitude]);
    }
}
