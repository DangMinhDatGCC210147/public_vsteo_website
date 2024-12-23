@extends('admin.layouts.layout-admin')
@section('title', 'Lecture Index')
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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashtrap</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-danger float-end">Monthly</span>
                        <h5 class="card-title mb-0">The student who does the most tests</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        @if ($person && $person->test_results_count !== 0)
                            <div class="col-8">
                                <h4 class="d-flex align-items-center mb-0">
                                    @if ($person != null)
                                        {{ $person->name }}
                                    @endif
                                </h4>
                            </div>
                            <div class="col-4 text-end">
                                <span class="text-muted">
                                    <h3>{{ $person->test_results_count }}</h3>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-info float-end">Per Day</span>
                        <h5 class="card-title mb-0">Highest number of correct reading questions</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h4 class="d-flex align-items-center mb-0">
                                @if ($highestReading != null)
                                    {{ $highestReading->name }}
                                @endif
                            </h4>
                        </div>
                        <div class="col-4 text-end">
                            <span class="text-muted">
                                @if ($highestReading != null)
                                    <h3>{{ $highestReading->reading_correctness }} câu</h3>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-success float-end">Per Day</span>
                        <h5 class="card-title mb-0">Highest number of correct listening questions</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h4 class="d-flex align-items-center mb-0">
                                @if ($highestListening != null)
                                    {{ $highestListening->name }}
                                @endif
                            </h4>
                        </div>
                        <div class="col-4 text-end">
                            <span class="text-muted">
                                @if ($highestListening != null)
                                    <h3>{{ $highestListening->listening_correctness }} câu</h3>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <!--end card body-->
            </div>
            <!--end card-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">All Time</span>
                        <h5 class="card-title mb-0">Number of Students who took the test</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                    @if ($person && $person->test_results_count !== 0)
                        <div class="col-8">
                            <h3 class="d-flex align-items-center mb-0">
                                {{ $count }}
                            </h3>
                        </div>
                        <div class="col-4 text-end">
                            <span class="text-muted">
                                <h4>{{ $totalStudentsCount }} sinh viên</h4>
                            </span>
                        </div>
                    @endif
                    </div>
                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div> <!-- end col-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">The test was done by day of the week</h4>
                    <div class="mt-4 chartjs-chart">
                        <canvas id="line-chart" height="90" data-colors="#1abc9c,#f1556c"></canvas>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

    <!-- end row-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Rank diligently</h4>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Total time accessed</th>
                                <th>Number of test done</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->account_id }}</td>
                                    <td>{{ $student->total_duration }}</td>
                                    <td>{{ $student->tests_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('line-chart').getContext('2d');
            var colors = document.getElementById('line-chart').getAttribute('data-colors').split(',');

            // Giả sử testsPerDay là một đối tượng JavaScript chứa dữ liệu số liệu cho các ngày
            var testsPerDay = @json($testsPerDay);

            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Object.keys(testsPerDay), // Labels are the days of the week
                    datasets: [{
                        label: 'The test was done by day of the week',
                        data: Object.values(testsPerDay), // Data points are the counts of tests
                        backgroundColor: colors[0],
                        borderColor: colors[1],
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1.0 // Đặt kích thước bước nhảy cho trục y là 1.0
                            }
                        }
                    }
                }
            });
        });
        </script>
@endsection
