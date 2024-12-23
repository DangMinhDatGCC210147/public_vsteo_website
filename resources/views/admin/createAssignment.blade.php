@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"> Create Assignment</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active"> New </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('tableAssignment.index') }}">
                <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
            </div>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title">Create Assignment</h5>
                <form id="assignmentTypetForm" action="{{ route('storeAssignmentType') }}" method="POST" class="p-4 border rounded">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="numberQuestion" class="form-label">Number of Question</label>
                                <input type="number" id="numberQuestion" class="form-control" min="1" placeholder="Enter number of questions" name="numberQuestion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="typeQuestion" class="form-label">Type of Question</label>
                                <select class="form-select" id="typeQuestion" name="typeQuestion" aria-label="Select question type" required>
                                    <option value="" disabled selected>Choose Question type</option>
                                    <option value="Multiplechoice">Multiple Choice</option>
                                    <option value="Fillintheblank">Fill in the blank</option>
                                    <option value="Truefalse">True-False-Not Given</option>
                                    <option value="Matching">Matching Headings</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-5 mt-1">
                                <button type="submit" class="btn btn-primary w-100 mt-4">Next</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
    {{-- <script src="{{ asset('admin/assets/js/selectSkillAndPart.js') }}"></script> --}}
@endsection
