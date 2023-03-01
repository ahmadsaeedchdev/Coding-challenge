<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * @return void
     * @description  the starter information like db seeder.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * @return User
     * @description  To get authenticated user token in header.
     */
    public function getAuthenticatedUserAndSetTokenInHeader(): User
    {
        $user = User::first();

        $token = $user->createToken('user token')->plainTextToken;

        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);

        return $user;
    }
}
