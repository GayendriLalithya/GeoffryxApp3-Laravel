<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'contact_no' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password123'), // Default password
            'user_type' => $this->faker->randomElement(['professional', 'customer', 'admin']),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted' => 0,
        ];
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a professional.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function professional()
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'professional',
        ]);
    }

    /**
     * Indicate that the user is a customer.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function customer()
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'customer',
        ]);
    }
}
