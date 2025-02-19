@extends('layouts.app')

@section('content')
    <!-- Task Menu -->
    <section class="comp-section">
        <div class="col-md-12">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified">
                    <li class="nav-item">
                        <a class="nav-link {{ $categoryId == 'All' ? 'active' : '' }}"
                            href="{{ route('task.index', ['category' => 'All']) }}">All</a>
                    </li>
                    @foreach ($categories as $category)
                        <li class="nav-item">
                            <a class="nav-link {{ $categoryId == $category->id ? 'active' : '' }}"
                                href="{{ route('task.index', ['category' => $category->id]) }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>


    <br>

    <!-- Cek jika tidak ada tugas -->
    @if ($groupedTasks->isEmpty())
        <div class="text-center">
            <p>Tidak ada tugas yang tersedia. Tambahkan tugas baru untuk memulai.</p>
        </div>
    @endif

    <!-- Looping melalui tugas yang dikelompokkan -->
    @foreach ($groupedTasks as $category => $tasks)
        @if ($category !== 'Completed')
            <!-- Kategori 'Completed' akan ditampilkan terpisah -->
            <h5 class="mt-4">{{ $category }}</h5>
            <div class="card-body">
                @foreach ($tasks as $task)
                    <div class="card card-primary card-outline task-card mb-2" data-completed="{{ $task->is_completed }}">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="task-item">
                                    <!-- Checkbox untuk menandai tugas selesai -->
                                    <form action="{{ route('task.toggleComplete', $task->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="checkbox" class="complete-task-checkbox" onchange="this.form.submit()"
                                            {{ $task->is_completed ? 'checked' : '' }}>
                                    </form>
                                    <span class="card-title {{ $task->is_completed ? 'completed-task' : '' }}">
                                        {{ $task->name }}
                                    </span>
                                </div>
                                <div class="card-tools">
                                    <p>{{ $task->description }}</p>
                                    <span class="badge bg-secondary">{{ $task->priority }}</span>
                                    <span
                                        class="badge bg-info">{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M Y') : 'No Deadline' }}</span>

                                    <button class="btn btn-tool btn-edit-task" data-id="{{ $task->id }}"
                                        data-toggle="modal" data-target="#edit-modal-lg">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form action="{{ route('deleteTask', $task->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-tool text-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach



    <!-- Tugas yang Selesai -->
    @if (!empty($groupedTasks['Completed']))
        <h5 class="mt-4">Tugas Selesai</h5>
        <div class="card-body">
            @foreach ($groupedTasks['Completed'] as $task)
                <div class="card card-primary card-outline task-card completed-task mb-2" data-completed="1">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <form action="{{ route('task.toggleComplete', $task->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox" class="complete-task-checkbox" onchange="this.form.submit()"
                                        checked>
                                </form>
                                <span class="card-title completed-task">{{ $task->name }}</span>
                            </div>
                            <div class="card-tools">
                                <form action="{{ route('deleteTask', $task->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-tool text-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>{{ $task->description }}</p>
                        <span class="badge bg-secondary">{{ $task->priority }}</span>
                        <span
                            class="badge bg-info">{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M Y') : 'No Deadline' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif


    <!-- Create Task -->
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
                            <select name="category_id" id="category_id" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
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

    <!-- Edit Task -->
    <div class="modal fade" id="edit-modal-lg">
        <div class="modal-dialog edit-modal-lg">
            <div class="modal-content">
                <form id="task-form" method="POST"
                    action="{{ isset($task) ? route('updateTask', ['id' => $task->id]) : '' }}">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal-title">Edit Task</h4>
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
                            <select name="category_id" id="category_id" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select name="priority" id="priority" class="form-control" required>
                                <option value="Low">Low
                                </option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deadline">Deadline</label>
                            <input type="datetime-local" name="deadline" id="deadline" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">`
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <button style="border-radius: 20px" type="button" class="btn btn-default" data-toggle="modal"
        data-target="#modal-lg" onclick="openCreateModal()">
        +
    </button>

    <!-- Style untuk Tugas Selesai -->
    <style>
        .completed-task {
            text-decoration: line-through;
            color: grey;
        }

        .task-item {
            display: flex;
            align-items: center;
            gap: 10px;
            /* Jarak antara checkbox dan teks */
            padding: 10px;
            border-radius: 8px;
            background-color: #f8f8f8;
            margin-bottom: 8px;
        }

        .complete-task-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .task-text {
            font-size: 16px;
        }

        .completed {
            text-decoration: line-through;
            color: gray;
        }

        .card-tools {
            margin-right: 20px;
            margin-bottom: 20px;
        }
    </style>

    <!-- Include Required Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Script -->
    <script>
        // Priority Dropdown Logic
        document.querySelectorAll('.current-priority').forEach(icon => {
            icon.addEventListener('click', function() {
                const dropdown = this.nextElementSibling;
                const isVisible = dropdown.style.display === 'block';
                document.querySelectorAll('.priority-dropdown').forEach(d => d.style.display = 'none');
                dropdown.style.display = isVisible ? 'none' : 'block';
            });
        });

        document.querySelectorAll('.priority-option').forEach(option => {
            option.addEventListener('click', function() {
                const taskId = this.closest('.task-priority').querySelector('.current-priority')
                    .getAttribute('data-task-id');
                const newPriority = this.getAttribute('data-prioritas');

                fetch(`/tasks/update-priority/${taskId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            prioritas: newPriority
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const currentIcon = this.closest('.task-priority').querySelector(
                                '.current-priority');
                            currentIcon.style.color = newPriority === 'tinggi' ? 'red' : (
                                newPriority === 'sedang' ? 'yellow' : 'blue');
                            currentIcon.setAttribute('title', newPriority.charAt(0).toUpperCase() +
                                newPriority.slice(1));
                            currentIcon.nextElementSibling.style.display = 'none';
                        } else {
                            alert('Gagal memperbarui prioritas!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memperbarui prioritas.');
                    });
            });
        });

        // Close dropdown if clicked outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.task-priority')) {
                document.querySelectorAll('.priority-dropdown').forEach(d => d.style.display = 'none');
            }
        });

        // Date Picker Logic
        document.querySelectorAll('.date-label').forEach(dateLabel => {
            dateLabel.addEventListener('click', function() {
                const taskId = this.getAttribute('data-task-id');
                const currentDate = this.getAttribute('data-task-date');
                const calendarPopup = document.getElementById('calendarPopup-' + taskId);

                calendarPopup.style.display = 'block';

                flatpickr("#datepicker-" + taskId, {
                    enableTime: true,
                    noCalendar: false,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                    minuteIncrement: 1, // Memungkinkan pemilihan menit per 1 menit
                    hourIncrement: 1, // Memungkinkan pemilihan jam per 1 jam
                    defaultDate: currentDate,
                    onChange: function(selectedDates, dateStr) {
                        dateLabel.innerText = dateStr;
                        dateLabel.setAttribute('data-task-date', dateStr);

                        fetch(`/tasks/update-date/${taskId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    tanggal: dateStr
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    dateLabel.innerText = data.formattedDate;
                                } else {
                                    alert('Terjadi kesalahan!');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                });

            });

            document.addEventListener('click', function(event) {
                const isClickInside = dateLabel.contains(event.target) || event.target.closest(
                    '.calendar-popup');
                if (!isClickInside) {
                    const calendarPopup = document.getElementById('calendarPopup-' + dateLabel.getAttribute(
                        'data-task-id'));
                    if (calendarPopup) {
                        calendarPopup.style.display = 'none';
                    }
                }
            });
        });

        // Task Options Logic
        document.querySelectorAll('.task-options .fa-ellipsis-v').forEach(icon => {
            icon.addEventListener('click', function(e) {
                e.stopPropagation();
                const taskId = this.getAttribute('id').split('-')[2];
                const menu = document.getElementById('options-menu-' + taskId);
                menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
            });
        });

        document.addEventListener('click', function(e) {
            const openMenus = document.querySelectorAll('.task-options-menu');
            openMenus.forEach(menu => {
                if (!menu.contains(e.target) && !e.target.classList.contains('fa-ellipsis-v')) {
                    menu.style.display = 'none';
                }
            });
        });

        // Edit Task Logic
        document.querySelectorAll('.edit-task').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const taskId = this.getAttribute('data-task-id');
                inlineEditTask(taskId);
            });
        });

        // Delete Task Logic
        document.querySelectorAll('.delete-task').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const taskId = this.getAttribute('data-task-id');
                deleteTask(taskId);
            });
        });

        document.querySelectorAll('.complete-task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const taskId = this.closest('form').action.split('/').pop();

                fetch(`/tasks/toggle-complete/${taskId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hapus tugas dari kategori sebelumnya
                            const taskCard = this.closest('.task-card');
                            taskCard.remove();

                            // Tambahkan ke bagian 'Tugas Selesai' jika selesai
                            if (data.is_completed) {
                                document.querySelector('.completed-tasks-section').appendChild(
                                    taskCard);
                                taskCard.classList.add('completed-task');
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });


        function inlineEditTask(taskId) {
            const taskTextElement = document.querySelector(`.task-item[data-task-id='${taskId}'] .task-text span`);
            const currentText = taskTextElement.textContent;

            taskTextElement.setAttribute('contenteditable', true);
            taskTextElement.classList.add('editable');

            const range = document.createRange();
            const selection = window.getSelection();
            range.selectNodeContents(taskTextElement);
            range.collapse(false);
            selection.removeAllRanges();
            selection.addRange(range);

            taskTextElement.focus();

            taskTextElement.addEventListener('blur', function() {
                const newText = taskTextElement.textContent.trim();
                updateTask(taskId, newText);
                taskTextElement.removeAttribute('contenteditable');
                taskTextElement.classList.remove('editable');
            });
        }

        function updateTask(taskId, newText) {
            fetch(`/tasks/${taskId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nama_tugas: newText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Task updated successfully', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error updating task:', error);
                    showAlert('Error updating task', 'danger');
                });
        }

        function deleteTask(taskId) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Tugas ini tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#fff',
                color: '#333',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl shadow-md p-4',
                    confirmButton: 'px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700',
                    cancelButton: 'px-4 py-2 rounded bg-gray-400 text-white hover:bg-gray-500'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/tasks/${taskId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Berhasil!', 'Tugas berhasil dihapus.', 'success');
                                document.querySelector(`.task-item[data-task-id='${taskId}']`).remove();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                        });
                }
            });
        }

        function showAlert(message, type = 'success') {
            Swal.fire({
                icon: type,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#fefefe',
                color: '#333',
                iconColor: '#4caf50',
                customClass: {
                    popup: 'rounded-xl shadow-lg p-3 border border-gray-200'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Tombol Tambah Task (Mode Create)
            document.getElementById('add-task-btn').addEventListener('click', function() {
                // Reset Form
                document.getElementById('task-form').reset();
                document.getElementById('task-id').value = "";
                document.getElementById('modal-title').textContent = "Create Task";
                document.getElementById('modal-save-btn').textContent = "Save";

                // Set Action Form ke Create
                document.getElementById('task-form').action = "{{ route('createTask') }}";
                document.getElementById('form-method').value = "POST"; // Method untuk create

                // Tampilkan Modal
                $('#modal-lg').modal('show');
            });
        });
        // Tombol Edit Task (Mode Edit)
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-task-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Ambil data dari tombol yang ditekan
                    let taskId = this.getAttribute('data-task-id');
                    let taskName = this.getAttribute('data-task-name');
                    let taskCategory = this.getAttribute('data-task-category');
                    let taskPriority = this.getAttribute('data-task-priority');
                    let taskDeadline = this.getAttribute('data-task-deadline');

                    // Isi nilai dalam modal
                    let form = document.getElementById('task-form');
                    form.setAttribute('action', `/updateTask/${taskId}`);

                    document.getElementById('name').value = taskName;
                    document.getElementById('category_id').value = taskCategory;
                    document.getElementById('priority').value = taskPriority;

                    // Format deadline agar sesuai dengan input datetime-local (jika ada deadline)
                    if (taskDeadline) {
                        let formattedDeadline = new Date(taskDeadline).toISOString().slice(0, 16);
                        document.getElementById('deadline').value = formattedDeadline;
                    } else {
                        document.getElementById('deadline').value = '';
                    }
                });
            });
        });

        $(document).on('click', '.btn-edit-task', function() {
            var taskId = $(this).data('id');

            $.ajax({
                url: "{{ url('/editTask') }}/" + taskId,
                type: "GET",
                success: function(data) {
                    $('#name').val(data.name);
                    $('#category_id').val(data.category_id);
                    $('#priority').val(data.priority);
                    $('#deadline').val(data.deadline);

                    // Update form action
                    $('#task-form').attr('action', "{{ url('/updateTask') }}/" + taskId);
                }
            });
        });
    </script>
@endsection
