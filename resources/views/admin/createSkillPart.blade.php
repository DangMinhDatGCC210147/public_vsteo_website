@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"> Create Skill Part and Question</h4>
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
            <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('questionBank.index') }}">
                    <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
            </div>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hướng dẫn chọn form để tạo câu hỏi cho các kỹ năng</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Bước 1:</strong> Chọn Skill Name tương ứng trước</p>
                        <p><strong>Bước 2:</strong> Chọn Part tương ứng muốn tạo cho skill</p>
                        <p><strong>Bước 3:</strong> Bấm nút "Next" để hiển thị form nhập câu hỏi</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Hướng dẫn tạo kỹ năng
                </button>
                <h5 class="header-title">Create Skill</h5>
                <form id="skillPartForm" action="{{ route('storeSkillPart') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="skillSelect" name="skillName"
                                    aria-label="Floating label select example" required>
                                    <option selected>Choose skill name</option>
                                    <option value="Listening">Listening</option>
                                    <option value="Reading">Reading</option>
                                    <option value="Writing">Writing</option>
                                    <option value="Speaking">Speaking</option>
                                </select>
                                <label for="skillSelect">Skill Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="partSelect" name="partName"
                                    aria-label="Floating label select example" required>
                                    <option selected>Choose part name</option>
                                </select>
                                <label for="partSelect">Part Name</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" id="submitBtn" class="btn btn-primary w-md" disabled>Next</button>
                    </div>
                </form>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
    <script src="{{ asset('admin/assets/js/selectSkillAndPart.js') }}"></script>
@endsection
