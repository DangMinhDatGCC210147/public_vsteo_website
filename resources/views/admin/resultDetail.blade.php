@extends('admin.layouts.layout-admin')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-secondary" href="{{ route('resultList.index') }}">
                <i class="mdi mdi-arrow-left-bold"></i>Turn back to previous page
            </a>
        </div>
    </div>
    <!-- end page title -->

    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <img src="{{ asset('storage/' . $student->image_file) }}" style="max-height: 300px;"
                            alt="Student Image" class="img-fluid rounded">
                    </div>

                    <h4 class="mb-3">Student Name: {{ $user->name }}</h4>
                    <h5>Student ID: {{ $user->account_id }}</h5>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-8">
                <div class="card rounded">
                    <div class="card-body" style="font-size: 0.9rem;">
                        <h3 class="card-title mb-3">Scores Overview</h3>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row">Test Name</th>
                                    <td>{{ $testResult->test_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Listening</th>
                                    <td>{{ number_format($testResult->computed_listening_score, 1) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Reading</th>
                                    <td>{{ number_format($testResult->computed_reading_score, 1) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Writing</th>
                                    <td>
                                        {{ $testResult->computed_writing_score != 0 ? number_format($testResult->computed_writing_score, 1) : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Speaking</th>
                                    <td>
                                        {{ $testResult->speaking != 0 ? number_format($testResult->speaking, 1) : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Overall</th>
                                    <td>
                                        @if ($testResult->computed_writing_score != 0.0 && $testResult->speaking !== null)
                                            @if (number_format($testResult->average_score, 1) >= 8.0)
                                                <div style="color: rgb(187, 187, 16)">
                                                    <strong>{{ number_format($testResult->average_score, 1) }}</strong>
                                                </div>
                                            @elseif (number_format($testResult->average_score, 1) >= 6.0)
                                                <div style="color: rgb(9, 195, 9)">
                                                    <strong>{{ number_format($testResult->average_score, 1) }}</strong>
                                                </div>
                                            @elseif(number_format($testResult->average_score, 1) < 6.0)
                                                <div style="color:rgb(225, 14, 14)">
                                                    <strong>{{ number_format($testResult->average_score, 1) }}</strong>
                                                </div>
                                            @endif
                                        @else
                                            <strong>{{ 'Not yet graded' }}</strong>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Date Completed</th>
                                    <td>{{ $testResult->created_at->format('Y-M-d H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body" style="font-size: 0.9rem;">
                        <div class="list-audio">
                            <h3 class="card-title mb-3">List audio</h3>
                            @if ($speakingResponses)
                                @foreach ($speakingResponses as $index => $response)
                                    <h5>Part {{ $index + 1 }}</h5>
                                    <audio controls controlsList="nodownload">
                                        <source src="{{ asset('storage/' . $response) }}" type="audio/mpeg"
                                            id="audioPlayer">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
