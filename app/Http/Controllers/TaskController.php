<?php  

namespace App\Http\Controllers;  

use App\Http\Requests\TaskRequest;  
use App\Services\TaskService;  
use App\Services\ProjectService;  
use Illuminate\Http\Request;  

class TaskController extends Controller  
{  
    protected $taskService;  
    protected $projectService;  

    public function __construct(TaskService $taskService, ProjectService $projectService)  
    {  
        $this->taskService = $taskService;  
        $this->projectService = $projectService;   
    }  

    public function index(Request $request)  
    {  
        $projects = $this->projectService->getAllProjects();  
        $tasks = $this->taskService->getAllTasks(10);  
        return view('tasks.index', compact('tasks', 'projects'));  
    }  

    public function create()  
    {  
        $projects = $this->projectService->getAllProjects();  
        return response()->json(['projects' => $projects]);  
    }  

    public function store(TaskRequest $request)  
    {  
        $this->taskService->createTask($request->validated());  
        return response()->json(['success' => true]);  
    }  

    public function edit($id)  
    {  
        $task = $this->taskService->findTaskById($id);  
        $projects = $this->projectService->getAllProjects();  
        return response()->json(['task' => $task, 'projects' => $projects]);  
    }  

    public function update(TaskRequest $request, $id)  
    {  
        $task = $this->taskService->findTaskById($id);  
        $this->taskService->updateTask($task, $request->validated());  
        return response()->json(['message' => 'Task updated successfully']);  
    }  

    public function destroy($id)  
    {  
        try {  
            $task = $this->taskService->findTaskById($id);  
            $this->taskService->deleteTask($task);  
            return response()->json(['success' => true]);  
        } catch (\Exception $e) {  
            \Log::error('Delete error: ' . $e->getMessage());  
            return response()->json(['error' => 'An error occurred'], 500);  
        }  
    }  

    public function search(Request $request)  
    {  
        try {  
            $tasks = $this->taskService->searchTasks(  
                $request->input('query'),  
                $request->input('project_id'),  
                $request->input('sort_column', 'id'),  
                $request->input('sort_order', 'desc'),  
                10  
            );  

            return response()->json([  
                'data' => $tasks->items(),  
                'links' => $tasks->appends($request->all())->links()->toHtml(),  
            ]);  
        } catch (\Exception $e) {  
            \Log::error('Search error: ' . $e->getMessage());  
            return response()->json(['error' => 'An error occurred'], 500);  
        }  
    }  
} 