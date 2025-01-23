<div class="modal fade" id="taskModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="taskForm">
                @csrf
                <input type="hidden" id="task_id" name="task_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add Task</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Task Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-control">
                            <option value="All">All</option>
                            <option value="Work">Work</option>
                            <option value="Diary">Diary</option>
                            <option value="Practice">Practice</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Deadline</label>
                        <input type="datetime-local" name="deadline" id="deadline" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
