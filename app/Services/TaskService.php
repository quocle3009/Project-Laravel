<?php

namespace App\Services;  

use App\Repositories\TaskRepository;  
use App\Models\Task;  

class TaskService  
{  
    protected $taskRepo;  

    public function __construct(TaskRepository $taskRepo)  
    {  
        $this->taskRepo = $taskRepo;  
    }  

    public function getAllTasks($paginate = 10)  
    {  
        return $this->taskRepo->all($paginate);  
    }  

    public function createTask(array $data)  
    {  
        return $this->taskRepo->create($data);  
    }  

    public function updateTask(Task $task, array $data)  
    {  
        return $this->taskRepo->update($task, $data);  
    }  

    public function deleteTask(Task $task)  
    {  
        return $this->taskRepo->delete($task);  
    }  

    public function findTaskById($id)  
    {  
        return $this->taskRepo->findById($id);  
    }  

    public function searchTasks($query, $projectId, $sortColumn, $sortOrder, $paginate = 10)  
    {  
        return $this->taskRepo->search($query, $projectId, $sortColumn, $sortOrder, $paginate);  
    }  
}  