<?php

namespace Database\Factories;

use App\Models\Rate;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'skill_id' => Skill::factory(),
            'value' => $this->faker->randomFloat(2, 0.1, 5),
        ];
    }
}
