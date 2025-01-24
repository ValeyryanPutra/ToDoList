@extends('layouts.app')

@section('content')
<!-- Task Menu -->
<section class="comp-section">
    <div class="col-md-12">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified">
                <li class="nav-item">
                    <a class="nav-link {{ $category == 'All' ? 'active' : '' }}" href="{{ route('task.index', ['category' => 'All']) }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $category == 'Work' ? 'active' : '' }}" href="{{ route('task.index', ['category' => 'Work']) }}">Work</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $category == 'Diary' ? 'active' : '' }}" href="{{ route('task.index', ['category' => 'Diary']) }}">Diary</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $category == 'Practice' ? 'active' : '' }}" href="{{ route('task.index', ['category' => 'Practice']) }}">Practice</a>
                </li>
            </ul>
        </div>
    </div>
</section>

<br>

<!-- Looping through grouped tasks -->
@foreach ($groupedTasks as $timeCategory => $tasks)
    <h5>{{ $timeCategory }}</h5>
    <div class="card-body">
        @if ($tasks->isEmpty())
            <p>Tidak ada tugas dalam kategori ini.</p>
        @else
            @foreach ($tasks as $task)
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">{{ $task->name }}</h5>
                        <div class="card-tools">
                            <button class="btn btn-tool" onclick="editTask({{ $task->id }})">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form action="{{ route('deleteTask', $task->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-tool text-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>{{ $task->description }}</p>
                        <span class="badge bg-secondary">{{ $task->priority }}</span>
                        <span class="badge bg-info">{{ $task->deadline ? $task->deadline->format('d M Y') : 'No Deadline' }}</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endforeach

<!-- Modal -->
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="task-form" method="POST" action="{{ route('createTask') }}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Create Task</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Task Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="All">All</option>
                            <option value="Work">Work</option>
                            <option value="Diary">Diary</option>
                            <option value="Practice">Practice</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select name="priority" id="priority" class="form-control" required>
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
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<button style="border-radius: 20px" type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg" onclick="openCreateModal()">
    +
</button>

<script>
    function editTask(id) {
        $.get(`/editTask/${id}`, function (task) {
            $('#task-form').attr('action', `/updateTask/${id}`);
            $('#task-form').append('<input type="hidden" name="_method" value="POST">');
            $('#modal-title').text('Edit Task');
            $('#name').val(task.name);
            $('#category').val(task.category);
            $('#priority').val(task.priority);
            $('#deadline').val(task.deadline);
            $('#modal-lg').modal('show');
        });
    }
</script>
@endsection
