<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Work;
use App\Models\User;

class ProjectCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_can_be_created()
{
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

    $this->assertNotNull($user->user_id, 'User ID is null');

    $project = Work::create([
        'description' => 'Test project description',
        'name' => 'Test Project',
        'user_id' => $user->user_id, // Explicitly reference user_id
        'location' => 'Test Location',
        'budget' => 50000.00,
        'start_date' => '2025-01-01',
        'end_date' => '2025-07-27',
        'status' => 'not started',
    ]);

    $this->assertNotNull($project->work_id, 'Project ID is null');

    $this->assertDatabaseHas('work', [
        'name' => 'Test Project',
        'description' => 'Test project description',
        'user_id' => $user->user_id,
        'location' => 'Test Location',
        'budget' => 50000.00,
        'start_date' => '2025-01-01',
        'end_date' => '2025-07-27',
        'status' => 'not started',
    ]);
}


}
