@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">
                    <h2>Writing Skill - {{ isset($skill_slug) ? "Update" : "Add" }}</h2>
                </h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item">
                            <a href="#">List of Skills</a>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12">
                <a href="{{ route('testSkills.show', $test_slug->slug) }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page
                </a>
            </div>            
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3>Writing Skill - {{ $test_slug->test_name }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($skill_slug) && isset($passages[0]) ? 
                            route('writing.questions.update', ['test_slug' => $test_slug, 'skill_slug' => $skill_slug]) : 
                            route('writing.questions.store', ['test_slug' => $test_slug, 'skill_id' => $skill_slug->id]) }}" 
                            method="POST">
                            @csrf
                            @if (isset($skill_slug) && isset($passages[0]))
                                @method('PUT')
                            @endif
                            <!-- Part 1 -->
                            <h4>Part 1</h4>
                            <div class="form-group mt-3">
                                <label for="question1" class="mb-3">Requirement 1:</label>
                                <input type="text" id="question1" name="question1" class="form-control" placeholder="Enter requirement here" required value="{{ $questions[0]->question_text ?? '' }}">
                            </div>
                            <div class="form-group mt-3">
                                <label for="passage1" class="mb-3">Passage 1:</label>
                                <textarea id="passage1" class="form-control" name="passage1" rows="5" placeholder="Enter passage here" required>{{ $passages[0]->reading_audio_file ?? '' }}</textarea>
                            </div>
                            <hr>

                            <!-- Part 2 -->
                            <h4>Part 2</h4>
                            <div class="form-group mt-3">
                                <label for="question2" class="mb-3">Requirement 2:</label>
                                <input type="text" id="question2" name="question2" class="form-control" placeholder="Enter requirement here" required value="{{ $questions[1]->question_text ?? '' }}">
                            </div>
                            <div class="form-group mt-3">
                                <label for="passage2" class="mb-3">Passage 2:</label>
                                <textarea id="passage2" class="form-control" name="passage2" rows="5" placeholder="Enter passage here" required>{{ $passages[1]->reading_audio_file ?? '' }}</textarea>
                            </div>
                            <hr>

                            <button type="submit" class="btn btn-primary">{{ isset($skill_slug) && isset($passages[0]) ? "Update" : "Save" }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
