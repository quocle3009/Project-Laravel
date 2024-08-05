<?php
namespace App\Services;  

use App\Repositories\taskRepository;  
use App\Models\Task;  

class TaskService  
{  
    protected $taskRepository;  

    public function __construct(taskRepository $taskRepository)  
    {  
        $this->taskRepository = $taskRepository;  
    }  

    public function getAllTasks($paginate = 10)  
    {  
        return $this->taskRepository->all($paginate);  
    }  

    public function createTask(array $data)  
    {  
        return $this->taskRepository->create($data);  
    }  

    public function updateTask(Task $task, array $data)  
    {  
        return $this->taskRepository->update($task, $data);  
    }  

    public function deleteTask(Task $task)  
    {  
        return $this->taskRepository->delete($task);  
    }  

    public function findTaskById($id)  
    {  
        return $this->taskRepository->findById($id);  
    }  

    public function searchTasks($query, $projectId, $sortColumn, $sortOrder, $paginate = 10)  
    {  
        return $this->taskRepository->search($query, $projectId, $sortColumn, $sortOrder, $paginate);  
    }  
}  