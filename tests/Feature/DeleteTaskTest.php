<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function authenticated_user_can_delete_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create();

        $response = $this->delete($this->getDeleteTaskRoute($task->id));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $response->assertStatus(200);
        // $response->assertRedirect('/tasks');
    }



    /** @test */
    public function unauthenticated_user_can_not_delete_task()
    {
        $task = Task::factory()->create();
        $response = $this->delete($this->getDeleteTaskRoute($task->id));
        $response->assertRedirect('/login');
    }


    public function getDeleteTaskRoute($id)
    {
        return route('tasks.destroy', $id);
    }
    public function getListTaskRoute()
    {
        return route("tasks.index");
    }
}
