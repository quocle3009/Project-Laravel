<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
class ProjectController extends Controller
{

public function getProjects()
{
    try {
        $projects = Project::all();
        return response()->json($projects);
    } catch (\Exception $e) {
        \Log::error('Error fetching projects: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred'], 500);
    }
}

}
