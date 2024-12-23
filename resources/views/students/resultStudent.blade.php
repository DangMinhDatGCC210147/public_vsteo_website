@extends('students.layouts.layout-student')

@section('content')
    <div data-test-id="{{ $testId }}" id="testContainer" hidden></div>
    <div class="px-3">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="card">
                <div class="row text-dark card-header navbar" id="navbar">
                    <div class="col-md-1">
                        <button class="btn btn-warning d-flex justify-content-center" id="theme-mode"><i
                                class="bx bx-moon font-size-18"></i></button>
                    </div>
                    <div class="col-md-2 d-flex justify-content-end">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="submit" class="btn btn-light"
                                onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();">
                                <span>Logout</span>
                            </button>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <button class="btn btn-info"
                                onclick="localStorage.clear(); window.location.href='{{ route('student.index') }}'">
                                Turn back
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <h2 class="text-center">TEST RESULT</h2>
            <div class="row d-flex justify-content-center mt-3">
                <div class="col-lg-6 col-xl-4">
                    <div class="card border-light">
                        <div class="card-body">
                            <div class="result-details">
                                <p><strong>Mã Số Sinh Viên:</strong> {{ $accountId }}</p>
                                <p><strong>Email:</strong> {{ $studentEmail }}</p>
                                <p><strong>Định dạng thi:</strong> Bậc 3 - 5</p>
                                <p><strong>Ngày thi:</strong> {{ date('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center" style="font-size: 1vw">
                <div class="col-lg-6 col-xl-4">
                    <!-- Simple card for Listening skill -->
                    <div class="card border-primary">
                        <div class="card-body">
                            <h3 class="card-title">Listening</h3>
                            <hr>
                            <p class="card-text"><strong>Tổng số câu trả lời đúng phần Listening:</strong>
                                {{ $correctAnswersListening }}/35</p>
                            <p class="card-text"><strong>Tỷ lệ trả lời đúng - Listening:</strong>
                                {{ number_format(($correctAnswersListening / 35) * 100, 2) }}%</p>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>Điểm - Listening:</strong></p>
                                </div>
                                <div class="col-6 d-flex justify-content-end align-items-center">
                                    <span class="badge bg-warning text-dark" style="font-size: 20px">
                                        <strong>{{ number_format($scoreListening, 2) }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-4">
                    <!-- Simple card for Reading skill -->
                    <div class="card border-success">
                        <div class="card-body">
                            <h3 class="card-title">Reading</h3>
                            <hr>
                            <p class="card-text"><strong>Tổng số câu trả lời đúng phần Reading:</strong>
                                {{ $correctAnswersReading }}/40</p>
                            <p class="card-text"><strong>Tỷ lệ trả lời đúng - Reading:</strong>
                                {{ number_format(($correctAnswersReading / 40) * 100, 2) }}%</p>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>Điểm - Reading:</strong></p>
                                </div>
                                <div class="col-6 d-flex justify-content-end align-items-center">
                                    <span class="badge bg-warning text-dark" style="font-size: 20px">
                                        <strong>{{ number_format($scoreReading, 2) }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Khi trang được tải, kiểm tra trạng thái
        window.onload = function() {
            var noReturn = localStorage.getItem('noReturn');
            if (noReturn === 'true') {
                history.pushState(null, null, location.href); // Cập nhật state hiện tại
                window.onpopstate = function() {
                    history.go(1);
                };
            }
        };
        localStorage.setItem('noReturn', 'true');
    </script>
@endsection
