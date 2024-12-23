@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">MARK THE STUDENT'S TEST</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Forms</a></li>
                        <li class="breadcrumb-item active">Mark</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-secondary" href="{{ route('resultList.index') }}">
                <i class="mdi mdi-arrow-left-bold"></i>Turn back to previous page
            </a>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-4 d-flex justify-content-center">
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-header d-flex justify-content-center">
                        <h3>Student's Information</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Fullname: </strong>{{ $student->name }}</p>
                        <p class="card-text"><strong>StudentID: </strong>{{ $student->account_id }}</p>
                        <p class="card-text"><strong>Status: </strong>
                            @if ($student->is_active == 1)
                                Active
                            @else
                                Inactive
                            @endif
                        </p>
                        <p class="card-text"><strong>Test Name: </strong>{{ $testName }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Mark for Writing and Speaking</h4>
                    <strong style="font-style: italic;" class="text-danger">Each part or task will base on scale 10. </strong>
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2">
                                <form action="{{ route('testResult.update') }}" method="POST" class="needs-validation"
                                    novalidate>
                                    @csrf
                                    <input type="hidden" name="studentId" value="{{ $studentId }}">
                                    <input type="hidden" name="testName" value="{{ $testName }}">
                                    <div class="card">
                                        <div class="row p-3">
                                            <h4 class="header-title">Writing Skill</h4>
                                            <hr>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="writing_part1" class="form-label">Writing Task 1 (Multiply factor 1):</label>
                                                    <input type="number" step="0.5" min="0" max="10"
                                                        class="form-control" id="writing_part1" name="writing_part1"
                                                        required value="{{ $resultId->writing_part1 }}">
                                                    <div class="invalid-feedback">
                                                        Please provide a valid score.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="writing_part2" class="form-label">Writing Task 2 (Multiply factor 2):</label>
                                                    <input type="number" step="0.5" min="0" max="10"
                                                        class="form-control" id="writing_part2" name="writing_part2"
                                                        value="{{ $resultId->writing_part2 }}" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a valid score.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="row p-3">
                                            <h4 class="header-title">Speaking Skill</h4>
                                            <hr>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="speaking_part1" class="form-label">Speaking Part 1 (Multiply factor 1):</label>
                                                    <input type="number" step="0.5" min="0" max="10"
                                                        class="form-control" id="speaking_part1" name="speaking_part1"
                                                        value="{{ $resultId->speaking_part1 }}" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a valid score.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="speaking_part2" class="form-label">Speaking Part 2 (Multiply factor 1):</label>
                                                    <input type="number" step="0.5" min="0" max="10"
                                                        class="form-control" id="speaking_part2" name="speaking_part2"
                                                        value="{{ $resultId->speaking_part2 }}" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a valid score.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="speaking_part3" class="form-label">Speaking Part 3 (Multiply factor 1):</label>
                                                    <input type="number" step="0.5" min="0" max="10"
                                                        class="form-control" id="speaking_part3" name="speaking_part3"
                                                        value="{{ $resultId->speaking_part3 }}" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a valid score.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
