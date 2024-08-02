<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }


    // Trong TasksController.php hoặc controller tương ứng
    public function index(Request $request)
    {
        $projects = Project::all();
        $tasks = $this->task->latest('id')->paginate(10);
        return view('tasks.index', compact('tasks', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return response()->json(['projects' => $projects]);
    }

    public function store(TaskRequest $request)
    {
        Task::create($request->validated());
        return response()->json(['success' => true]);
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        return response()->json(['task' => $task, 'projects' => $projects]);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return response()->json(['message' => 'Task updated successfully']);
    }

    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Delete error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }


    public function search(Request $request)
    {
        try {
            $tasks = $this->buildTaskQuery($request)->paginate(10, ['*'], 'page', $request->input('page', 1));
            return response()->json([
                'data' => $tasks->items(),
                'links' => $tasks->appends($request->all())->links()->toHtml(),
            ]);
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    protected function buildTaskQuery(Request $request)
    {
        $allowedSortColumns = ['id', 'name', 'content'];
        $sortColumn = in_array($request->get('sort_column', 'id'), $allowedSortColumns) ? $request->get('sort_column') : 'id';

        $allowedSortOrders = ['asc', 'desc'];
        $sortOrder = in_array(strtolower($request->get('sort_order', 'desc')), $allowedSortOrders) ? $request->get('sort_order') : 'desc';

        return Task::query()
            ->when($request->input('query'), function ($q, $query) {
                return $q->where('name', 'like', "%{$query}%");
            })
            ->when($request->input('project_id'), function ($q, $projectId) {
                return $q->where('project_id', $projectId);
            })
            ->with('project')
            ->orderBy($sortColumn, $sortOrder)
            ->latest('id');
    }
}
