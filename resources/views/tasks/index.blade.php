@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <main id="main" class="main">
            <div class="row mb-3">
                <div class="col-md-6">
                    <button class="btn btn-primary" id="add-task-btn">Add Task</button>
                </div>
                <div class="col-md-6 d-flex justify-content-end align-items-center">  
                    <form id="filter-form" class="form-inline">  
                        <div class="input-group">  
                            
                            <div class="position-relative">  
                                <select id="project-filter" class="form-control ml-2">  
                                    <option value="">All Projects</option>  
                                    @foreach ($projects as $project)  
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>  
                                    @endforeach  
                                </select>  
                                <i class="bi bi-chevron-down position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>  
                            </div>  
                            
                            <input id="search-input" type="text" class="form-control" placeholder="Search tasks">  
                        </div>  
                    </form>  
                </div>  
            </div>
            <div class="row justify-content-center">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>
                                <a href="#" class="sort-link" data-column="id" data-order="desc">
                                    ID
                                    <i class="bi bi-sort-alpha-up sort-icon desc" style="display: none;"></i>
                                    <i class="bi bi-sort-alpha-down sort-icon asc" style="display: none;"></i>
                                </a>
                            </th>
                            <th style="width: 15%">
                                <a href="#" class="sort-link" data-column="name" data-order="desc">
                                    Name
                                    <i class="bi bi-sort-alpha-up sort-icon desc" style="display: none;"></i>
                                    <i class="bi bi-sort-alpha-down sort-icon asc" style="display: none;"></i>
                                </a>
                            </th>
                            <th style="width: 60%">
                                <a href="#" class="sort-link" data-column="content" data-order="desc">
                                    Content
                                    <i class="bi bi-sort-alpha-up sort-icon desc" style="display: none;"></i>
                                    <i class="bi bi-sort-alpha-down sort-icon asc" style="display: none;"></i>
                                </a>
                            </th>
                            <th style="width: 12%">
                                <a href="#" class="sort-link" data-column="project" data-order="desc">
                                    Project
                                    <i class="bi bi-sort-alpha-up sort-icon desc" style="display: none;"></i>
                                    <i class="bi bi-sort-alpha-down sort-icon asc" style="display: none;"></i>
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>



                    <tbody id="tasks-table-body">
                        @foreach ($tasks as $task)
                            <tr class="align-middle" data-task-id="{{ $task->id }}">
                                <td class="text-center">{{ $task->id }}</td>
                                <td>{{ $task->name }}</td>
                                <td style="height: 80px;width: 50%">{{ $task->content }}</td>
                                <td>{{ $task->project ? $task->project->name : 'No Project' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-warning edit-btn" data-task-id="{{ $task->id }}"
                                        style="display:inline-block;margin-left: 3px"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-danger delete-btn"
                                        data-task-id="{{ $task->id }}"><i class="bi bi-trash"></i></button>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="d-flex justify-content-center">
                    <div id="pagination-links">
                        {{ $tasks->appends(['query' => request()->get('query')])->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Confirm Delete -->
    @include('tasks.delete-modal')


    <!-- Modal Create Task -->
    @include('tasks.create-modal')

    <!-- Modal Edit Task -->
    @include('tasks.edit-modal')

    <!-- Script tasks -->
    <script src="assets{{'/js/tasks.js'}}"></script>


 
@endsection
