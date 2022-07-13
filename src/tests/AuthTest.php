<?php

namespace Tests\Controllers;

use App\Models\Phone;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_auth()
    {
        $user = User::factory()->create();

        $response = $this->json('GET', route('user.show'), [], [
            'x-user-phones' => $user->phones->random()->phone,
        ]);

        $response->seeStatusCode(Response::HTTP_OK);
    }

    public function test_auth_without_header()
    {
        $user = User::factory()->create();

        $response = $this->json('GET', route('user.show'), [], [
            // empty headers
        ]);

        $response->seeStatusCode(Response::HTTP_UNAUTHORIZED);
    }
}
