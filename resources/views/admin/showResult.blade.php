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
                        <li class="breadcrumb-item active">List of Result</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Date filter form -->
    <div class="card mt-2">
        <div class="row m-3">
            <div class="col-12">
                <h4>Download student's responses by Date</h4>
                <br>
                <form action="{{ route('download.filterdate') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="start_date">Start Date and Time:</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
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
                            <button type="submit" class="btn btn-warning">Download Response</button>
                        </div>
                    </div>
                </form>
                <br>
                <hr>
                <h4>Export Excel by Date</h4>
                <br>
                <form action="{{ route('export.filterdate') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="start_date">Start Date and Time:</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
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
                            <button type="submit" class="btn btn-warning">Export Excel Result</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">List of Results</h4>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                {{-- <th>Test Name</th> --}}
                                <th>Listening</th>
                                <th>Reading</th>
                                <th>Writing</th>
                                <th>Speaking</th>
                                <th>Overall</th>
                                <th>Date Finish</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($testResults as $result)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $result->student->name }}</td>
                                    <td>{{ $result->student->account_id }}</td>
                                    {{-- <td>{{ $result->test_name }}</td> --}}
                                    <td>{{ number_format($result->computed_listening_score, 1) }}</td>
                                    <td>{{ number_format($result->computed_reading_score, 1) }}</td>
                                    <td>
                                        @if (number_format($result->computed_writing_score, 1) != 0.0)
                                            {{ number_format($result->computed_writing_score, 1) }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($result->speaking_part1 != null || $result->speaking_part2 != null || $result->speaking_part3 != null)
                                            {{ number_format($result->speaking, 1) }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($result->computed_writing_score != 0.0 && $result->speaking !== null)
                                            @if( number_format($result->average_score, 1) >= 8.0)
                                                <div style="color: rgb(187, 187, 16)"><strong>{{ number_format($result->average_score, 1) }}</strong></div>
                                            @elseif ( number_format($result->average_score, 1) >= 6.0)
                                                <div style="color: rgb(9, 195, 9)"><strong>{{ number_format($result->average_score, 1) }}</strong></div>
                                            @elseif( number_format($result->average_score, 1) < 6.0)
                                                <div style="color:rgb(225, 14, 14)"><strong>{{ number_format($result->average_score, 1) }}</strong></div>
                                            @endif
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td>{{ $result->created_at->format('Y-M-d H:i') }}</td>
                                    <td>
                                        <a
                                            href="{{ route('mark.response', [
                                                'studentId' => $result->student->id,
                                                'testName' => $result->test_name,
                                                'resultId' => isset($result->computed_listening_score) ? $result->id : '',
                                            ]) }}" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                data-bs-content="Mark score">
                                            <i class="mdi mdi-lead-pencil mdi-24px" style="color:orange"></i>
                                        </a>
                                        <a
                                            href="{{ route('download.response', ['studentId' => $result->student_id, 'testName' => $result->test_name]) }}" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                            data-bs-content="Download">
                                            <i class="mdi mdi-download mdi-24px"></i>
                                        </a>
                                        <a href="{{ route('resultList.details', ['id' => $result->id]) }}" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                            data-bs-content="View detail">
                                            <i class="mdi mdi-eye mdi-24px" style="color:rgb(22, 22, 174)"></i>
                                        </a>
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
    <script>
        // Chạy script này khi tài liệu đã sẵn sàng
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('error'))
            const errorMessage = @json(session('error'));
                Swal.fire({
                    html: `
                        <h3>Oops...</h3>
                        <h4>${errorMessage}</h4>
                    `,
                    icon: 'error',
                });
            @endif
        });
    </script>
@endsection
