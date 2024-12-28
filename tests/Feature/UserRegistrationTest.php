<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        // Simulate user registration via POST request
        $response = $this->post('/register', [
            'name' => 'Test User',
            'contact_no' => '1234567890',
            'address' => '123 Test Street',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'customer', // Default role for users
        ]);

        // Assert the response status (302 for redirect after registration)
        $response->assertStatus(302);

        // Assert the user is saved in the database
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'name' => 'Test User',
            'contact_no' => '1234567890',
            'address' => '123 Test Street',
            'user_type' => 'customer',
        ]);
    }

    public function test_user_cannot_register_with_missing_fields()
    {
        // Simulate user registration with missing fields
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
        ]);

        // Assert the session has validation errors for missing fields
        $response->assertSessionHasErrors(['password', 'contact_no', 'address', 'user_type']);
    }
}
