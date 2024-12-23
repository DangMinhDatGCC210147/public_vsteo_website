@extends('admin.layouts.layout-admin')

@section('content')
    @php
        $borderColors = [
            'Listening' => 'border-primary',
            'Speaking' => 'border-danger',
            'Reading' => 'border-success',
            'Writing' => 'border-secondary',
        ];
        $buttonColors = [
            'Listening' => 'btn-primary',
            'Speaking' => 'btn-danger',
            'Reading' => 'btn-success',
            'Writing' => 'btn-secondary',
        ];
        $badgeColors = [
            'Listening' => 'bg-primary',
            'Speaking' => 'bg-danger',
            'Reading' => 'bg-success',
            'Writing' => 'bg-secondary',
        ];
    @endphp

    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">
                    <h2>Test Skills for {{ $test->test_name }}</h2>
                </h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="{{ route('tableTest.index') }}">List of Test</a></li>
                        <li class="breadcrumb-item active">4 skills</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12"><a href="{{ route('tableTest.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left-bold"></i>Turn back to previous page</a></div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <hr>
            @foreach ($test->testSkills as $skill)
                <div class="col-lg-6 col-xl-6">
                    <!-- Simple card for each skill -->
                    <div class="card {{ $borderColors[$skill->skill_name] ?? 'border-primary' }}">
                        <div class="card-body">
                            <h3 class="card-title">{{ $skill->skill_name }}</h3>
                            <hr>
                            <p class="card-text">Duration: {{ \Carbon\Carbon::parse($skill->time_limit)->format('H:i') }}
                                hrs</p>
                            <p class="card-text">
                            <div class="row">
                                <div class="col-6">Number of questions:</div>
                                <div class="col-6 d-flex justify-content-end">
                                    <span class="badge {{ $badgeColors[$skill->skill_name] ?? 'bg-primary' }}"
                                        style="font-size: 0.8vw">
                                        {{ $skill->questions_count }}
                                    </span>
                                </div>
                            </div>
                            </p>
                            <hr>
                            <a href="{{ $skill->questions_count > 0 ? route('skill.edit.questions', ['test_slug' => $test->slug, 'skill_slug' => $skill->slug]) : route('skill.add.questions', ['test_slug' => $test->slug, 'skill_slug' => $skill->slug]) }}"
                                class="btn {{ $buttonColors[$skill->skill_name] ?? 'border-primary' }} waves-effect waves-light">
                                {{ $skill->questions_count > 0 ? 'Edit Questions' : 'Add Questions' }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
