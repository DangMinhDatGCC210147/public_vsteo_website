@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">{{ isset($room) ? 'Edit Student In Room' : 'Create Student In New Room' }}</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Forms</a></li>
                        <li class="breadcrumb-item active">{{ isset($room) ? 'Edit Student In' : 'Create Student In' }} Room</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-secondary" href="{{ route('room.index') }}">
                <i class="mdi mdi-arrow-left-bold"></i>Turn back to previous page
            </a>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Import Students from Excel, Sample File <a
                            href="{{ asset('admin\assets\SampleImportStudentToRoom.xlsx') }}"> Click here</a></h4>
                    <div class="card-content">
                        <form class="form-horizontal" action="{{ route('room.importStudents', ['id' => $room->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2 row">
                                <label class="col-md-2 col-form-label" for="file">Select File</label>
                                <div class="col-md-10">
                                    <input type="file" id="file" class="form-control" name="file" required>
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <button type="submit" class="btn btn-success w-xl">Import Students</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">ADD STUDENT ONE BY ONE</h4>
                    <div class="card-content">
                        <form class="form-horizontal" action="{{ route('room.addStudent', ['id' => $room->id]) }}"
                            method="POST">
                            @csrf
                            <div class="mb-2 row">
                                <label class="col-md-2 col-form-label" for="account_id">Student ID</label>
                                <div class="col-md-10">
                                    <input type="text" placeholder="Enter student ID here" id="account_id"
                                        class="form-control" name="account_id" required>
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <button type="submit" class="btn btn-primary w-xl">Add Student</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="header-title mt-4">Students in Room</h4>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">List of Rooms</h4>
                    <a href="{{ route('room.downloadFiles', $room->id) }}" class="btn btn-primary mb-3">Download Responses</a>
                    <a href="{{ route('room.export', $room->id) }}" class="btn btn-success mb-3">Export Excel</a>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studentsInRoom as $student)
                                <tr id="student-row-{{ $student->id }}" class="{{ $student->is_active ? '' : 'inactive-row' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->account_id }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @if ($student->is_active)
                                            <div class="badge bg-success">
                                                Active
                                            </div>
                                        @else
                                            <div class="badge bg-danger">
                                                Inactive
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="remove-student" data-room-id="{{ $room->id }}"
                                            data-student-id="{{ $student->id }}">
                                            <i class="mdi mdi-delete-empty mdi-24px" style="color: rgb(206, 25, 25)"></i>
                                        </a>
                                        {{-- <a href="{{ route('room.addStudentForm', ['id' => $room->id]) }}">
                                            <i class="mdi mdi-account-multiple-plus mdi-24px mx-2" style="color: rgb(19, 225, 201)"></i>
                                        </a>
                                        <a href="{{ route('room.edit', ['id' => $room->id]) }}"><i
                                            class="mdi mdi-lead-pencil mdi-24px"></i></a>
                                        <a href="{{ route('room.destroy', $room->id) }}"
                                            onclick="event.preventDefault();
                                                    if(confirm('Are you sure you want to delete this room?')) {
                                                        document.getElementById('delete-form-{{ $room->id }}').submit();
                                                    }">
                                            <i class="mdi mdi-delete-empty mdi-24px" style="color: rgb(206, 25, 25)"></i>
                                        </a>
                                        <form id="delete-form-{{ $room->id }}"
                                            action="{{ route('room.destroy', $room->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <style>
        .inactive-row {
            opacity: 0.3;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.remove-student').forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const roomId = this.getAttribute('data-room-id');
                    const studentId = this.getAttribute('data-student-id');
                    const row = document.getElementById(`student-row-${studentId}`);

                    fetch(`/room/${roomId}/remove-student/${studentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (row) {
                                    row.remove();
                                }
                            } else {
                                alert('Failed to remove the student.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to remove the student.');
                        });
                });
            });
        });
    </script>
@endsection
