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
                        <li class="breadcrumb-item active">List of Lecturers</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @if(auth()->user()->role == 0)
        <!-- end page title -->
        <div class="row">
            <div class="col-6 d-flex justify-content-start">
                <form id="inactive-students-form" class="mx-2" action="{{ route('lecturers.inactive') }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-danger" onclick="inactiveStudents()" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Deactive account">Deactivate Lecturers</button>
                </form>
                <form id="active-students-form" action="{{ route('lecturers.active') }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-warning" onclick="activeStudents()" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Activate account">Activate Lecturers</button>
                </form>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="{{ route('createInstructor.create') }}" class="btn btn-info" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Create new account">Create</a>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">List of Lecturers</h4>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Lecturer ID</th>
                                <th>Status</th>
                                @if(auth()->user()->role == 0)
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lecturers as $lecturer)
                                <tr class="{{ $lecturer->is_active ? '' : 'inactive-row' }}">
                                    <td><input type="checkbox" name="student_ids[]" value="{{ $lecturer->id }}" {{ $lecturer->is_active ? 'checked' : '' }}></td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $lecturer->name }}</td>
                                    <td>{{ $lecturer->email }}</td>
                                    <td>{{ $lecturer->account_id }}</td>
                                    <td>
                                        @if ($lecturer->is_active)
                                            <div class="badge bg-success">
                                                Active
                                            </div>
                                        @else
                                            <div class="badge bg-danger">
                                                Inactive
                                            </div>
                                        @endif
                                    </td>
                                    @if(auth()->user()->role == 0)
                                    <td>
                                        <a href="{{ route('createInstructor.edit', $lecturer->slug) }}" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Edit"><i
                                                class="mdi mdi-lead-pencil mdi-24px"></i></a>
                                        <a href="{{ route('createInstructor.destroy', $lecturer->slug) }}"
                                            onclick="event.preventDefault();
                                                    if(confirm('Are you sure you want to delete this test?')) {
                                                        document.getElementById('delete-form-{{ $lecturer->slug }}').submit();
                                                    }" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Delete">
                                            <i class="mdi mdi-delete-empty mdi-24px" style="color: red"></i>
                                        </a>
                                        <form id="delete-form-{{ $lecturer->slug }}"
                                            action="{{ route('createInstructor.destroy', $lecturer->slug) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
    <style>
        .inactive-row {
            opacity: 0.3;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select/Deselect all checkboxes
            document.getElementById('checkAll').addEventListener('change', function() {
                let checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            // Check/uncheck "checkAll" based on individual checkbox status
            let checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    let allChecked = Array.from(checkboxes).every(chk => chk.checked);
                    document.getElementById('checkAll').checked = allChecked;
                });
            });

            // Initialize the "checkAll" checkbox status on page load
            let allChecked = Array.from(checkboxes).every(chk => chk.checked);
            document.getElementById('checkAll').checked = allChecked;
        });

        function inactiveStudents() {
            let checkboxes = document.querySelectorAll('input[name="student_ids[]"]:not(:checked)');
            let form = document.getElementById('inactive-students-form');

            checkboxes.forEach(checkbox => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'student_ids[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });

            form.submit();
        }

        function activeStudents() {
            let checkboxes = document.querySelectorAll('input[name="student_ids[]"]:checked');
            let form = document.getElementById('active-students-form');

            checkboxes.forEach(checkbox => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'student_ids[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });

            form.submit();
        }
    </script>
@endsection
