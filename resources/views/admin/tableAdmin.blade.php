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
                        <li class="breadcrumb-item active">List of Admin</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    @if(auth()->user()->role == 0)
        <div class="row">
            <div class="col-12 d-flex justify-content-end">
                <a href="{{ route('createAdmin.create') }}" class="btn btn-info" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Create new account">Create</a>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">List of Admins</h4>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Staff ID</th>
                                @if (auth()->user()->role == 0)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->account_id }}</td>
                                    @if (auth()->user()->role == 0)
                                        <td>
                                            <a href="{{ route('createAdmin.edit', $admin->slug) }}" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Edit"><i
                                                    class="mdi mdi-lead-pencil mdi-24px"></i></a>
                                            <a href="{{ route('createAdmin.destroy', $admin->slug) }}"
                                                onclick="event.preventDefault();
                                                        if(confirm('Are you sure you want to delete this test?')) {
                                                            document.getElementById('delete-form-{{ $admin->slug }}').submit();
                                                        }" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Delete">
                                                <i class="mdi mdi-delete-empty mdi-24px" style="color: red"></i>
                                            </a>
                                            <form id="delete-form-{{ $admin->slug }}"
                                                action="{{ route('createAdmin.destroy', $admin->slug) }}" method="POST"
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
@endsection
