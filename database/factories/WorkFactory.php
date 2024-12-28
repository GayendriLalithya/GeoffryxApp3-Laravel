<?php

namespace Database\Factories;

use App\Models\Work;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkFactory extends Factory
{
    protected $model = Work::class;

    public function definition()
    {
        return [
            'description' => $this->faker->paragraph,
            'name' => $this->faker->words(3, true),
            'user_id' => User::factory(),
            'location' => $this->faker->address,
            'budget' => $this->faker->randomFloat(2, 1000, 100000),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'status' => 'not started',
        ];
    }
}
