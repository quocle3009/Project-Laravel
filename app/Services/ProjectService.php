<?php  

namespace App\Services;  

use App\Models\Project;  

class ProjectService  
{  
    public function getAllProjects()  
    {  
        return Project::all();  
    }  
}  

