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
                        <li class="breadcrumb-item active">List of Tests</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @if (auth()->user()->role == 0)
        <div class="card">
            <div class="card-body">
                <h4>Delete Test by Date</h4>
                <br>
                <form action="{{ route('delete-tests') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('DELETE')
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="start_date">Start Date and Time:</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid date and time.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="end_date">End Date and Time:</label>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                                <div class="invalid-feedback">
                                    Please provide a valid date and time.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-warning">Delete Tests</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        <!-- end page title -->
        @if (auth()->user()->role == 0)
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <button class="btn btn-danger" id="deleteAll">Delete All</button>
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
                            <p><strong>In addition, when agreeing to delete all files, the speaking, writing files and the
                                    reading and
                                    listening exercises will be deleted.</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteAll">Yes, delete it!</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">List of Tests</h4>

                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Test Name</th>
                                    <th>Duration</th>
                                    <th>Create At</th>
                                    @if (auth()->user()->role == 0)
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tests as $test)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $test->test_name }}</td>
                                        <td>{{ $test->duration }}</td>
                                        <td>{{ $test->created_at }}</td>
                                        @if (auth()->user()->role == 0)
                                            <td>
                                                {{-- <a href="{{ route('downloadTest', ['slug' => $test->slug]) }}">
                                            <i class="mdi mdi-file-export mdi-24px"></i>
                                        </a> --}}
                                                <a href="{{ route('test.destroy', $test->slug) }}"
                                                    onclick="event.preventDefault();
                                                    if(confirm('Are you sure you want to delete this test?')) {
                                                        document.getElementById('delete-form-{{ $test->slug }}').submit();
                                                    }">
                                                    <i class="mdi mdi-delete-empty mdi-24px"
                                                        style="color: rgb(206, 25, 25)"></i>
                                                </a>
                                                <form id="delete-form-{{ $test->slug }}"
                                                    action="{{ route('test.destroy', $test->slug) }}" method="POST"
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
        <script>
            document.getElementById('deleteAll').addEventListener('click', function() {
                $('#confirmationModal').modal('show');
            });

            document.getElementById('confirmDeleteAll').addEventListener('click', function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/delete-all-tests', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        $('#confirmationModal').modal('hide');
                        $('#showForm').append(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        All tests and related student records have been deleted.
                    </div>
                `);
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    })
                    .catch(error => {
                        $('#confirmationModal').modal('hide');
                        $('#showForm').append(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Something went wrong. Please try again later.
                    </div>
                `);
                    });
            });
        </script>
    @endsection
