<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Restaurant;

class GeoTest extends TestCase
{
    use RefreshDatabase;

    public function testAccessPosition()
    {
        $restaurant = $this->createRestaurant();
        $position   = [
            'lat'   =>'34.982158',
            'lng'   => '135.962708',
        ];
        $restaurant->positions = $position;
        $values = $restaurant->positions;
        $this->assertEquals($position['lat'], $values['latitude']);
        $this->assertEquals($position['lng'], $values['longitude']);
    }
}
