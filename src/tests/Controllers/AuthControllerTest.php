<?php

namespace Tests\Controllers;

use App\Models\Contact;
use App\Models\Phone;
use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_register()
    {
        $phone = Phone::factory()->make()->phone;

        $response = $this->json('POST', route('auth.login'), [
            'phone' => $phone,
        ]);

        $response->seeStatusCode(Response::HTTP_CREATED);
        $this->seeInDatabase('phones', [
            'phone' => $phone,
        ]);
    }

    public function test_can_auth()
    {
        $user = User::factory()->has(
            Phone::factory()->sequence(fn () => [
                'is_main' => true,
            ])
        )->create();

        $response = $this->json('POST', route('auth.login'), [
            'phone' => $user->phones->where('is_main', true)->first()->phone,
        ]);

        $response->seeStatusCode(Response::HTTP_OK);
//        $response->notSeeInDatabase('users', [
//            'login_at' => null,
//        ]);
    }
}
