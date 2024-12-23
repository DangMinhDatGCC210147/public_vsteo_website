@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">{{ isset($room) ? 'Edit Room' : 'Create New Room' }}</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Forms</a></li>
                        <li class="breadcrumb-item active">{{ isset($room) ? 'Edit' : 'Create' }} Room</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-secondary" href="{{ route('room.index') }}">
                <i class="mdi mdi-arrow-left-bold"></i>Turn back to previous page
            </a>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ isset($room) ? 'Edit Room' : 'Create Room' }}</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2">
                                <form class="form-horizontal" action="{{ isset($room->id) ? route('room.update', ['id' => $room->id]) : route('room.store') }}" method="POST">
                                    @csrf
                                    @if(isset($room->id))
                                        @method('PUT')
                                    @endif
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="simpleinput">Room Name</label>
                                        <div class="col-md-10">
                                            <input type="text" placeholder="Enter room name here" id="simpleinput" class="form-control" value="{{ $room->room_name ?? '' }}" name="room_name" required>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="example-capacity">Capacity</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="number" min="0" max="30" placeholder="Enter capacity here" id="example-capacity" name="capacity" value="{{ $room->capacity ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="example-start-date">Start Time</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="datetime-local" name="start_time" id="example-start-date" value="{{ isset($room->start_time) ? date('Y-m-d\TH:i', strtotime($room->start_time)) : '' }}">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-md-2 col-form-label" for="example-end-date">End Time</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="datetime-local" name="end_time" id="example-end-date" value="{{ isset($room->end_time) ? date('Y-m-d\TH:i', strtotime($room->end_time)) : '' }}">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-10">
                                            <hr>
                                            <button type="submit" class="btn btn-primary w-xl">{{ isset($room) ? 'Update' : 'Create' }}</button>
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
