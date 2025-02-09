@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-8">
            <div class="card bg-white">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-sm-12">
        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_event">Create
            Event</a>
    </div>

    <!-- Modal Tambah Event -->
    <div id="add_event" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createEventForm">
                        @csrf
                        <div class="form-group">
                            <label>Event Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Event Date <span class="text-danger">*</span></label>
                            <div class="cal-icon">
                                <input class="form-control" type="date" name="date" required>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Event -->
    <div id="edit_event" class="modal custom-modal fade none-border" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Event</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="event_id">
                        <div class="form-group">
                            <label>Event Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" id="event_name" required>
                        </div>
                        <div class="form-group">
                            <label>Event Date <span class="text-danger">*</span></label>
                            <div class="cal-icon">
                                <input class="form-control" type="date" name="date" id="event_date" required>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button type="submit" class="btn btn-success save-event">Update</button>
                            <button type="button" class="btn btn-danger delete-event" id="deleteEventBtn">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: "{{ route('calendar') }}", // API untuk mendapatkan event
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                selectable: true,
                select: function (info) {
                    // Tampilkan modal untuk menambah event
                    $('#add_event').modal('show');
                    $('#createEventForm input[name="date"]').val(info.startStr);
                },
                eventClick: function (info) {
                    // Tampilkan modal untuk edit event
                    var event = info.event;
                    $('#edit_event').modal('show');
                    $('#editEventForm #event_id').val(event.id);
                    $('#editEventForm #event_name').val(event.title);
                    $('#editEventForm #event_date').val(event.startStr);
                }
            });

            calendar.render();

            // Tambah Event
            $('#createEventForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('calendar.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#add_event').modal('hide');
                        calendar.refetchEvents();
                    },
                    error: function (response) {
                        alert('Error: ' + response.responseJSON.message);
                    }
                });
            });

            // Edit Event
            $('#editEventForm').submit(function (e) {
                e.preventDefault();
                var id = $('#editEventForm #event_id').val();
                $.ajax({
                    url: "/events/" + id,
                    method: "PUT",
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#edit_event').modal('hide');
                        calendar.refetchEvents();
                    },
                    error: function (response) {
                        alert('Error: ' + response.responseJSON.message);
                    }
                });
            });

            // Hapus Event
            $('#deleteEventBtn').click(function () {
                var id = $('#editEventForm #event_id').val();
                if (confirm('Are you sure you want to delete this event?')) {
                    $.ajax({
                        url: "/events/" + id,
                        method: "DELETE",
                        success: function (response) {
                            $('#edit_event').modal('hide');
                            calendar.refetchEvents();
                        },
                        error: function (response) {
                            alert('Error: ' + response.responseJSON.message);
                        }
                    });
                }
            });
        });
    </script>
@endsection
