@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">{{ isset($user) ? 'Edit' : 'Create' }} Admin Account</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">{{ isset($user) ? 'Edit' : 'Create New' }} Account</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('tableAdmin.index') }}">
                    <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a></div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            @if (!isset($user))
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Hướng dẫn tạo cấu trúc file excel để tạo tài khoản với số lượng nhiều</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Bước 1:</strong> Tạo một file excel rỗng</p>
                                <p><strong>Bước 2:</strong> Nhập dữ liệu tương ứng như sau (không cần nhập tên cột vào file):</p>
                                <ul>
                                    <li>Cột 1: Nhập đầy đủ họ tên</li>
                                    <li>Cột 2: Nhập địa chỉ email (email đăng kí tài khoản dùng để đăng nhập)</li>
                                    <li>Cột 3: Nhập mã số nhân viên</li>
                                    <li>Cột 4: Nhập mật khẩu (Ex: 12345678)</li>
                                </ul>
                                <p><strong>Bước 3:</strong> Bấm nút <strong>"Register by Excel"</strong> màu trắng để đăng kí</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('createAdmin.excel.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- File upload for Excel -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="header-title">Upload Excel File to Register Multiple Admin</h4>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Hướng dẫn tạo file Excel
                            </button>
                            <div class="mb-2 row">
                                <label class="col-md-2 col-form-label" for="excel_file">Excel File</label>
                                <div class="col-md-10">
                                    <input type="file" id="excel_file" class="form-control" name="excel_file" required>
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <hr>
                                    <button type="submit" class="btn btn-light">Register by Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ isset($user) ? 'Edit' : 'Create' }} New Admin Account</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2">
                                <form class="form-horizontal"
                                    action="{{ isset($user) ? route('createAdmin.update', $user->slug) : route('createInstructor.store') }}"
                                    method="POST">
                                    @csrf
                                    @if (isset($user))
                                        @method('PUT')
                                    @endif
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="simple-input">Full Name</label>
                                        <div class="col-md-10">
                                            <input type="text" id="simple-input" class="form-control"
                                                value="{{ isset($user) ? $user->name : '' }}" placeholder="Full name"
                                                name="name" required>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="simpleinput">Email</label>
                                        <div class="col-md-10">
                                            <input type="email" id="simpleinput" class="form-control"
                                                value="{{ isset($user) ? $user->email : '' }}" placeholder="Email"
                                                name="email" required>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="example-email">Admin ID</label>
                                        <div class="col-md-10">
                                            <input type="text" id="example-email" class="form-control"
                                                placeholder="Admin ID" name="account_id"
                                                value="{{ isset($user) ? $user->account_id : '' }}">
                                        </div>
                                    </div>
                                    <input type="hidden" name="role" value="0">
                                    @if (!isset($user))
                                        <div class="mb-2 row">
                                            <label class="col-md-2 col-form-label" for="example-password">Password</label>
                                            <div class="col-md-10">
                                                <input type="password" class="form-control" id="example-password"
                                                    value="" placeholder="Password" name="password"
                                                    {{ isset($user) ? 'hidden' : 'required' }}>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="mb-2 row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-10">
                                            <hr>
                                            <button type="submit" class="btn btn-primary w-xl">{{ isset($user) ? 'Update' : 'Register' }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <!-- end row -->
                </div>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
@endsection
