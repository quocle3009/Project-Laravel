<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-task-form">
                    <div class="mb-3">
                        <label for="edit-task-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-task-name" required>
                        @error('task-name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit-task-content" class="form-label">Content</label>
                        <textarea class="form-control" id="edit-task-content" rows="3" required></textarea>
                        @error('task-content')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit-task-project" class="form-label">Project</label>
                        <select id="edit-task-project" class="form-control">
                            <option value="">No Project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" id="edit-task-id">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
