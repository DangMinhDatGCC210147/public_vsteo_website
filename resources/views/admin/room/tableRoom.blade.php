@extends('admin.layouts.layout-admin')

@section('content')
    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Dashboard</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">List of Rooms</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- end page title -->
        <div class="row">
            <div class="buttons">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('room.create') }}" class="btn btn-info mx-2" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Create new room">Create</a>
                    @if (auth()->user()->role == 0)
                        <button class="btn btn-danger" id="deleteAll" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Delete all room">Delete All</button>
                    @endif
                </div>
            </div>
        </div>
        <div id="showForm"></div>
        <!-- Modal -->
        <!-- Modal Bootstrap 5 -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete all tests and related student records? This action cannot be
                            undone.</p>
                        <p><strong>In addition, when agreeing to delete all files, the speaking, writing files and the reading and
                            listening exercises will be deleted.</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteAll">Yes, delete it!</button>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">List of Tests</h4>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Room Name</th>
                                <th>Capacity</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Participants</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rooms as $room)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $room->room_name }}</td>
                                    <td>{{ $room->capacity }}</td>
                                    <td>{{ $room->start_time }}</td>
                                    <td>{{ $room->end_time }}</td>
                                    <td><h3><span class="badge bg-success">{{ $room->students_count }}</span></h3></td>
                                    <td>
                                        <a href="{{ route('room.addStudentForm', ['id' => $room->id]) }}" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Add students">
                                            <i class="mdi mdi-account-multiple-plus mdi-24px mx-2" style="color: rgb(19, 225, 201)"></i>
                                        </a>
                                        <a href="{{ route('room.edit', ['id' => $room->id]) }}" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Edit"><i
                                            class="mdi mdi-lead-pencil mdi-24px"></i></a>
                                        <a href="{{ route('room.destroy', $room->id) }}"
                                            onclick="event.preventDefault();
                                                    if(confirm('Are you sure you want to delete this room?')) {
                                                        document.getElementById('delete-form-{{ $room->id }}').submit();
                                                    }" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Delete">
                                            <i class="mdi mdi-delete-empty mdi-24px" style="color: rgb(206, 25, 25)"></i>
                                        </a>
                                        <form id="delete-form-{{ $room->id }}"
                                            action="{{ route('room.destroy', $room->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
@endsection
