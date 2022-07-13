<?php

namespace Database\Factories;

use App\Models\Phone;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'lang' => $this->faker->locale,

            'twitter' => $this->faker->url,
            'github' => $this->faker->url,
            'instagram' => $this->faker->url,
            'reddit' => $this->faker->url,
            'facebook' => $this->faker->url,
            'telegram' => $this->faker->url,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // Creating phones for user
            Phone::factory()->for($user, 'owner')->count(mt_rand(1, 2))->create();
        });
    }
}
