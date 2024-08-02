<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetListTaskTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /** @test */
    use RefreshDatabase;

    public function test_example(): void
    {
        // Create a user
        $user = User::factory()->create();
        Task::factory()->create();

        // Simulate the user being authenticated
        $response = $this->actingAs($user)->get('/tasks');

        // Assert that the response status is 200
        $response->assertStatus(200);
    }
}