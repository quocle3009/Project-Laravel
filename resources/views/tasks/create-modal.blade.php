<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Create New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="create-task-form">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="task-name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="task-name" name="name" >
                        @error('task-name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="mb-3">
                        <label for="task-content" class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="task-content" rows="3" name="content" ></textarea>
                        @error('task-content')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="mb-3">
                        <label for="task-project" class="form-label">Project <span class="text-danger">*</span></label>
                        <select class="form-control" id="task-project" name="project_id" >
                            <option value="">Select Project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
