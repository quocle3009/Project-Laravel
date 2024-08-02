<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
/** @test */
/** @test */
public function authenticated_user_can_edit_task()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $task = Task::factory()->create();

    $updatedData = [
        'name' => 'Updated Task Name',
        'content' => 'Update Task Content'
    ];

    $response = $this->putJson($this->getEditTaskRoute($task->id), $updatedData);
    $response->assertStatus(200);
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'name' => 'Updated Task Name',
        'content' => 'Update Task Content'
    ]);
    $response->assertJson([
        'message' => 'Task updated successfully'
    ]);
}



    /** @test */
    public function unauthenticated_user_can_not_edit_task()
    {
        $task = Task::factory()->create();
        $response = $this->put($this->getEditTaskRoute($task->id), $task->toArray());
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_not_edit_task_form_if_field_is_null()
    {
        $this->actingAs(User::factory()->create());
        $task = Task::factory()->create();
        $data = [
            'name' => null,
            'content' => $task->content
        ];

        $response = $this->put($this->getEditTaskRoute($task->id), $data);
        $response->assertSessionHasErrors(['name']);
    }
    public function getEditTaskRoute($id)
    {
        return route('tasks.update', $id);
    }
}
