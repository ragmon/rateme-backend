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

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function test_can_get_own_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', route('user.show'));

        $response->seeStatusCode(Response::HTTP_OK);
    }

    public function test_can_get_friend_profile()
    {
        $userPhone = $this->faker->phoneNumber;
        $friendPhone = $this->faker->phoneNumber;

        $user = User::factory()->has(
            Phone::factory()->sequence(fn() => [
                'phone' => $userPhone
            ])
        )->has(
            Contact::factory()->has(
                Phone::factory()->sequence(fn() => [
                    'phone' => $friendPhone,
                ])
            )
        )->create();

        $friendUser = User::factory()->has(
            Phone::factory()->sequence(fn() => [
                'phone' => $friendPhone,
            ])
        )->has(
            Contact::factory()->has(
                Phone::factory()->sequence(fn() => [
                    'phone' => $userPhone
                ])
            )
        )->create();

        $response = $this->actingAs($user)->json('GET', route('user.show', ['id' => $friendUser->id]));

        $response->seeStatusCode(Response::HTTP_OK);
        $this->assertStringContainsString("\"id\":$friendUser->id", $response->response->content());
    }

    public function test_cant_get_not_friend_profile()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', route('user.show', ['id' => $otherUser->id]));

        $response->seeStatusCode(Response::HTTP_FORBIDDEN);
    }

    public function test_can_upload_photo_png()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.png');

        $response = $this->actingAs($user)->post(route('user.upload_photo'), [
            'image' => $file,
        ]);

        $response->seeStatusCode(Response::HTTP_CREATED);
        $response->seeJsonStructure([
            'data' => [
                'id',
                'path',
                'is_main',
            ]
        ]);
        $this->seeInDatabase('photos', [
            'owner_id' => $user->id,
            'owner_type' => User::class,
        ]);

        $this->clearThumbnails();
    }

    public function test_can_upload_photo_jpg()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->post(route('user.upload_photo'), [
            'image' => $file,
        ]);

        $response->seeStatusCode(Response::HTTP_CREATED);
        $response->seeJsonStructure([
            'data' => [
                'id',
                'path',
                'is_main',
            ]
        ]);
        $this->seeInDatabase('photos', [
            'owner_id' => $user->id,
            'owner_type' => User::class,
        ]);

        $this->clearThumbnails();
    }

    public function test_can_upload_photo_jpeg()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpeg');

        $response = $this->actingAs($user)->post(route('user.upload_photo'), [
            'image' => $file,
        ]);

        $response->seeStatusCode(Response::HTTP_CREATED);
        $response->seeJsonStructure([
            'data' => [
                'id',
                'path',
                'is_main',
            ]
        ]);
        $this->seeInDatabase('photos', [
            'owner_id' => $user->id,
            'owner_type' => User::class,
            //
        ]);

        $this->clearThumbnails();
    }

    private function clearThumbnails()
    {
        $files = glob(storage_path('photos/*'));
        foreach($files as $file){ // iterate files
            if(is_file($file)) {
                unlink($file); // delete file
            }
        }
    }

    public function test_can_update_names()
    {
        $user = User::factory()->create();
        $data = [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
        ];

        $response = $this->actingAs($user)->json('PUT', route('user.update'), $data);

        $response->seeStatusCode(Response::HTTP_OK);
        $this->seeInDatabase('users', array_merge([
            'id' => $user->id
        ], $data));
    }

    public function test_update_lang()
    {
        $user = User::factory()->create();
        $data = [
            'lang' => $this->faker->locale,
        ];

        $response = $this->actingAs($user)->json('PUT', route('user.update'), $data);

        $response->seeStatusCode(Response::HTTP_OK);
        $this->seeInDatabase('users', array_merge([
            'id' => $user->id
        ], $data));
    }

    public function test_update_social_networks()
    {
        $user = User::factory()->create();
        $data = [
            'twitter' => $this->faker->url,
            'github' => $this->faker->url,
            'instagram' => $this->faker->url,
            'reddit' => $this->faker->url,
            'facebook' => $this->faker->url,
            'telegram' => $this->faker->url,
        ];

        $response = $this->actingAs($user)->json('PUT', route('user.update'), $data);

        $response->seeStatusCode(Response::HTTP_OK);
        $this->seeInDatabase('users', array_merge([
            'id' => $user->id
        ], $data));
    }

    public function test_change_phone()
    {
        $user = User::factory()->create();
        $data = [
            'phone' => $this->faker->phoneNumber,
        ];

        $response = $this->actingAs($user)->json('PATCH', route('user.change_phone'), $data);

        $response->seeStatusCode(Response::HTTP_OK);
        $this->seeInDatabase('phones', array_merge([
            'owner_type' => User::class,
            'owner_id' => $user->id,
            'is_main' => true,
        ], $data));
    }
}
