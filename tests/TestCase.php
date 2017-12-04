<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * call json api
     * @param  string $method [description]
     * @param  string $uri    [description]
     * @param  array  $params [description]
     * @param  [type] $user   [description]
     * @return [type]         [description]
     */
    public function api(
        string $method,
        string $uri,
        array $params = [],
        User $user = null
    ) {
        $method = strtoupper($method);
        auth()->logout();
        $uri = ltrim($uri, '/');
        $uri = "/api/{$uri}";
        return $this->json($method, $uri, $params);
    }

    public function createUser() : User
    {
        return factory(User::class)->create();
    }
}
