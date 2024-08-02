<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;


class SearchTaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->user = User::factory()->create();
        // dd($this->user); 
        $this->actingAs($this->user);
    }
    
    public function test_search_with_invalid_sort_column_defaults_to_id()
    {
        $project = Project::factory()->create();
        $task1 = Task::factory()->create(['name' => 'Task 1', 'project_id' => $project->id]);
        $task2 = Task::factory()->create(['name' => 'Task 2', 'project_id' => $project->id]);
        $task3 = Task::factory()->create(['name' => 'Task 3', 'project_id' => $project->id]);
    
        $response = $this->getJson('/api/tasks/search?sort_column=invalid_column');
    
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links']);
        $tasks = $response->json()['data'];
    
        // Kiểm tra số lượng nhiệm vụ
        $this->assertCount(3, $tasks); 
    
        // Kiểm tra thứ tự sắp xếp theo ID
        $this->assertEquals($task1->id, $tasks[2]['id']);
        $this->assertEquals($task2->id, $tasks[1]['id']);
        $this->assertEquals($task3->id, $tasks[0]['id']);
    }

    public function test_search_returns_filtered_tasks()
    {
        $project = Project::factory()->create();
        $task1 = Task::factory()->create(['name' => 'Test Task 1', 'project_id' => $project->id]);
        $task2 = Task::factory()->create(['name' => 'Another Task', 'project_id' => $project->id]);

        $response = $this->getJson('/api/tasks/search?query=Test&project_name=' . $project->name);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links']);
        $tasks = $response->json()['data'];

        // Kiểm tra rằng chỉ có một nhiệm vụ được trả về
        $this->assertCount(1, $tasks);
        $this->assertEquals('Test Task 1', $tasks[0]['name']);
    }


    

    public function test_search_with_invalid_sort_order_defaults_to_desc()
    {
        $project = Project::factory()->create();
        $task1 = Task::factory()->create(['name' => 'Task 1', 'project_id' => $project->id]);
        $task2 = Task::factory()->create(['name' => 'Task 2', 'project_id' => $project->id]);

        $response = $this->getJson('/api/tasks/search?sort_order=invalid_order');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links']);
        $tasks = $response->json()['data'];

        // Kiểm tra rằng các nhiệm vụ được sắp xếp theo thứ tự giảm dần
        $this->assertEquals($task2->id, $tasks[0]['id']);
        $this->assertEquals($task1->id, $tasks[1]['id']);

    }
}
