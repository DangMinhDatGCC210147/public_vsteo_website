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
                        <li class="breadcrumb-item active">List of Parts</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-3">
        <a href="{{ route('questionBank.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page
        </a>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            {{-- <a href="{{ route('createStudent.create') }}" class="btn btn-info">Create</a> --}}
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">List of Parts</h4>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Skill Name</th>
                                <th>Part Name</th>
                                <th>Passage</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($writingQuestionBank as $index => $writing)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $writing->skill_name }}</td>
                                    <td>{{ str_replace('_', ' ', $questions[$index]) }}</td>
                                    <td>{{ char_limit($passages[$index], 80) }}</td>
                                    <td>{{ $writing->created_at }}</td>
                                    <td>
                                        <a href="{{ route('editQuestionWriting', ['test_slug' => $writing->slug, 'part_name' => $questions[$index]]) }}">
                                            <i class="mdi mdi-lead-pencil mdi-24px" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                            data-bs-content="Edit"></i>
                                        </a>
                                        <a href="{{ route('test.skill.destroy', $writing->slug) }}"
                                            onclick="event.preventDefault();
                                                    if(confirm('Are you sure you want to delete this test skill?')) {
                                                        document.getElementById('delete-form-{{ $writing->slug }}').submit();
                                                    }" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                data-bs-content="Delete">
                                            <i class="mdi mdi-delete-empty mdi-24px" style="color: red"></i>
                                        </a>
                                        <form id="delete-form-{{ $writing->slug }}"
                                            action="{{ route('test.skill.destroy', $writing->slug) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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
@endsection
