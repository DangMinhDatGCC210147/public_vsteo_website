@extends('admin.layouts.layout-admin')

@section('content')
    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Question Bank List</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">List of Question By Each Part</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex justify-content-start mb-3">
                <a href="{{ route('create.skill.part') }}" class="btn btn-info">Create</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-xl-6">
                <!-- Simple card for each skill -->
                <div class="card border-primary">
                    <div class="card-body">
                        <h3 class="card-title">Listening</h3>
                        <hr>
                        <p class="card-text">List Part and question of Listening</p>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('questionBank.listening') }}"
                                    class="btn btn-primary waves-effect waves-light">
                                    View
                                </a>
                            </div>
                            <div class="col-md-9 d-flex justify-content-around pt-2">
                                @foreach ($listeningParts as $part => $count)
                                    <div><strong>{{ $part = str_replace("_", " ", $part) }}</strong>: <div class="badge bg-primary fs-5">{{ $count }}</div></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-6">
                <!-- Simple card for each skill -->
                <div class="card border-danger">
                    <div class="card-body">
                        <h3 class="card-title">Speaking</h3>
                        <hr>
                        <p class="card-text">List Part and question of Speaking</p>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('questionBank.speaking') }}" class="btn btn-danger waves-effect waves-light">
                                    View
                                </a>
                            </div>
                            <div class="col-md-9 d-flex justify-content-around pt-2">
                                @foreach ($speakingParts as $part => $count)
                                    <div><strong>{{ $part = str_replace("_", " ", $part) }}</strong>: <div class="badge bg-danger fs-5">{{ $count }}</div></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-6">
                <!-- Simple card for each skill -->
                <div class="card border-success">
                    <div class="card-body">
                        <h3 class="card-title">Reading</h3>
                        <hr>
                        <p class="card-text">List Part and question of Reading</p>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('questionBank.reading') }}" class="btn btn-success waves-effect waves-light">
                                    View
                                </a>
                            </div>
                            <div class="col-md-9 d-flex justify-content-around pt-2">
                                @foreach ($readingParts as $part => $count)
                                    <div><strong>{{ $part = str_replace("_", " ", $part) }}</strong>: <div class="badge bg-success fs-5">{{ $count }}</div></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-6">
                <!-- Simple card for each skill -->
                <div class="card border-secondary">
                    <div class="card-body">
                        <h3 class="card-title">Writing</h3>
                        <hr>
                        <p class="card-text">List Part and question of Writing</p>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('questionBank.writing') }}" class="btn btn-secondary waves-effect waves-light">
                                    View
                                </a>
                            </div>
                            <div class="col-md-9 d-flex justify-content-around pt-2">
                                @foreach ($writingParts as $part => $count)
                                    <div><strong>{{ $part = str_replace("_", " ", $part) }}</strong>: <div class="badge bg-secondary fs-5">{{ $count }}</div></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
