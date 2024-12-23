@extends('students.layouts.layout-student')

@section('content')
    <div class="container-fluid">
        <div class="px-3">
            <div class="card">
                <div class="row text-dark card-header navbar" id="navbar">
                    <div class="col-md-1">
                        <button class="btn btn-warning d-flex justify-content-center" id="theme-mode"><i
                                class="bx bx-moon font-size-18"></i></button>
                    </div>
                    <div class="col-md-2 d-flex justify-content-end">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="submit" class="btn btn-light"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span>Logout</span>
                            </button>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <button class="btn btn-info" onclick="window.location.href='{{ route('student.index') }}'">Turn back</button>
                        </div>
                    </div>
                </div>
            </div>
            @if ($student)
                <div class="row d-flex justify-content-center">
                    <h1>User Profile</h1>
                    <div class="col-md-4">
                        <div class="card text-success">
                            <div class="card-body">
                                <h3 class="card-title">GENERAL INFORMATION</h3>
                                <hr>
                                <p class="card-text">Full Name: {{ $student->name }}</p>
                                <p class="card-text">Email: {{ $student->email }}</p>
                                <p class="card-text">Account ID: {{ $student->account_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <p>No student information available.</p>
            @endif
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
                                        <th>Test Name</th>
                                        <th>Listening</th>
                                        <th>Reading</th>
                                        <th>Writing</th>
                                        <th>Speaking</th>
                                        <th>Overall</th>
                                        <th>Date Finish</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($testResults as $result)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $result->student->name }}</td>
                                            <td>{{ $result->student->account_id }}</td>
                                            <td>{{ $result->test_name }}</td>
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
                                                        <div style="color: yellow">{{ number_format($result->average_score, 1) }}</div>
                                                    @elseif ( number_format($result->average_score, 1) >= 6.0 && number_format($result->average_score, 1) < 8.0)
                                                        <div style="color: rgb(9, 195, 9)">{{ number_format($result->average_score, 1) }}</div>
                                                    @elseif( number_format($result->average_score, 1) < 6.0)
                                                        <div style="color:rgb(225, 14, 14)">{{ number_format($result->average_score, 1) }}</div>
                                                    @endif
                                                @else
                                                    {{ '-' }}
                                                @endif
                                            </td>
                                            <td>{{ $result->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
        </div>
    </div>
@endsection
