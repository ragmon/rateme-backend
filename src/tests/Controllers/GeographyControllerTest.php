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

class GeographyControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_countries()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', route('geography.countries'));

        $response->seeStatusCode(Response::HTTP_OK);
        $response->seeJsonStructure([
            [
                'id',
                'sortname',
                'name_en',
            ]
        ]);
    }
}
