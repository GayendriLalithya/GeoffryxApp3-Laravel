<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Notification;
use App\Models\User;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_can_be_created()
    {
        // Create a user manually
        $user = User::create([
            'name' => 'Test User',
            'contact_no' => '1234567890',
            'address' => '123 Test Street',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
            'user_type' => 'customer',
            'remember_token' => null,
            'deleted' => 0,
        ]);

        // Ensure the user is created
        $this->assertNotNull($user->user_id, 'User ID is null');

        // Create a notification manually
        $notification = Notification::create([
            'user_id' => $user->user_id,
            'title' => 'Payment Received',
            'message' => 'Your payment of $5000 has been successfully received.',
            'status' => 'unread',
        ]);

        // Ensure the notification is created
        $this->assertNotNull($notification->notification_id, 'Notification ID is null');

        // Assert the notification is saved in the database
        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->user_id,
            'title' => 'Payment Received',
            'message' => 'Your payment of $5000 has been successfully received.',
            'status' => 'unread',
        ]);
    }
}
