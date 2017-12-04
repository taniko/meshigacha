<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Allergy;

class AllergyTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate()
    {
        $data = factory(Allergy::class)->make()->toArray();
        $count = Allergy::count();
        $response = $this->api('POST', 'allergies', $data);
        $response->assertStatus(200);
        $this->assertEquals($count + 1 , Allergy::count());
    }

    public function testFind()
    {
        $allergy = factory(Allergy::class)->create();
        $response = $this->api('GET', "allergies/{$allergy->id}");
        $response->assertStatus(200);
        $this->assertEquals($allergy->name, ($response->json())['name']);
    }

    public function testSearch()
    {
        for ($i = 0; $i < 10; $i++) {
            factory(Allergy::class)->create();
        }
        $allergy = Allergy::get()->random();
        $response = $this->api('GET', 'allergies', ['name' => $allergy->name]);
        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json()));
    }

    public function testFoods()
    {
        for ($i = 0; $i < 2; $i++) {
            $allergy = factory(Allergy::class)->create();
            for ($j = 0; $j < 5; $j++) {
                $food = $this->createFood();
                $food->attachAllergy($allergy);
            }
        }
        $allergy = Allergy::get()->random();
        $response = $this->api('GET', "allergies/{$allergy->id}/foods");
        $response->assertStatus(200);
        $this->assertEquals(5, count($response->json()));
    }
}
