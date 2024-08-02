<?php

namespace App\Repositories;  

use App\Models\Task;  

class TaskRepository  
{  
    public function all($paginate = 10)  
    {  
        return Task::latest('id')->paginate($paginate);  
    }  

    public function create(array $data)  
    {  
        return Task::create($data);  
    }  

    public function update(Task $task, array $data)  
    {  
        return $task->update($data);  
    }  

    public function delete(Task $task)  
    {  
        return $task->delete();  
    }  

    public function findById($id)  
    {  
        return Task::findOrFail($id);  
    }  

    public function search($query = null, $projectId = null, $sortColumn = 'id', $sortOrder = 'desc', $paginate = 10)  
    {  
        return Task::query()  
            ->when($query, function ($q) use ($query) {  
                return $q->where('name', 'like', "%{$query}%");  
            })  
            ->when($projectId, function ($q) use ($projectId) {  
                return $q->where('project_id', $projectId);  
            })  
            ->with('project')  
            ->orderBy($sortColumn, $sortOrder)  
            ->latest('id')  
            ->paginate($paginate);  
    }  
}  