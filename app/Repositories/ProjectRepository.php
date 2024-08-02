<?php
namespace App\Repositories;  

use App\Models\Project;  

class ProjectRepository  
{  
    public function all($paginate = 10)  
    {  
        return Project::paginate($paginate);  
    }  

    public function create(array $data)  
    {  
        return Project::create($data);  
    }  

    public function update(Project $project, array $data)  
    {  
        $project->update($data);  
        return $project;  
    }  

    public function delete(Project $project)  
    {  
        return $project->delete();  
    }  

    public function findById($id)  
    {  
        return Project::findOrFail($id);  
    }  
}  
