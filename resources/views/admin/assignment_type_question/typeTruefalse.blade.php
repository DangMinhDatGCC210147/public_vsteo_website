@extends('admin.layouts.layout-admin')

@section('content')
<div class="py-3 py-lg-4">
    <div class="row">
        <div class="col-lg-6">
            <h4 class="page-title mb-0">TRUE/FALSE/NOT GIVEN</h4>
        </div>
        <div class="col-lg-6">
            <div class="d-none d-lg-block">
                <ol class="breadcrumb m-0 float-end">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">{{ isset($assignment) ? 'Edit' : 'New' }} Question For True/False/Not Given Type of Assignment</li>
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
<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Assignment - {{ isset($assignment) ? 'Edit' : 'New' }} Question For True/False/Not Given Type</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="p-2">
                            <form action="{{ isset($assignment) ? route('updateAssignment', $assignment->id) : route('storeTruefalseType') }}" method="POST">
                                @csrf
                                @if (isset($assignment))
                                    @method('PUT')
                                @endif
                                <!-- Assignment Fields -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Assignment Details</h5>

                                        <div class="form-group mt-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control" value="{{ $assignment->title ?? '' }}" placeholder="Enter title here" required>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="description">Passage</label>
                                            <textarea name="description" id="description" rows="10" style="resize: vertical;" class="form-control" placeholder="Enter Passage here">{{ $assignment->description ?? '' }}</textarea>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="duration">Duration (in minutes)</label>
                                            <input type="number" name="duration" id="duration" class="form-control" min="0" value="{{ $assignment->duration ?? '' }}"
                                            placeholder="Ex: 10, 15, 20, 30, ...">
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="isEnable">Activation state</label>
                                            <select name="isEnable" id="isEnable" class="form-control" required>
                                                <option value="1" {{ isset($assignment) && $assignment->isEnable ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ isset($assignment) && !$assignment->isEnable ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="show_detailed_feedback">Show answers after submit: </label>
                                            <input type="hidden" name="show_detailed_feedback" value="0">
                                            <input type="checkbox" name="show_detailed_feedback" id="show_detailed_feedback" class="form-check-input" value="1" {{ isset($assignment) && $assignment->show_detailed_feedback ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                <!-- True/False/Not Given Questions -->
                                @if (isset($assignment))
                                    @foreach ($questions as $i => $question)
                                    {{-- @dd($question) --}}
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">Question {{ $i + 1 }}</h5>

                                                <div class="form-group">
                                                    {{-- <label for="question_text_{{ $i }}">Question Text</label> --}}
                                                    <textarea name="questions[{{ $i }}][question_text]" id="question_text_{{ $i }}" class="form-control" rows="3" required>{{ $question->question_text }}</textarea>
                                                </div>

                                                <input type="hidden" name="questions[{{ $i }}][question_type]" value="true_false">

                                                <div class="form-group mt-3">
                                                    <label>Correct Answer</label>
                                                    <select name="questions[{{ $i }}][correct_answer]" class="form-control" required>
                                                        <option value="true" {{ $question->trueFalse->correct_answer === 'true' ? 'selected' : '' }}>True</option>
                                                        <option value="false" {{ $question->trueFalse->correct_answer === 'false' ? 'selected' : '' }}>False</option>
                                                        <option value="not_given" {{ $question->trueFalse->correct_answer === 'not_given' ? 'selected' : '' }}>Not Given</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @for ($i = 0; $i < $quantity; $i++)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">Question {{ $i + 1 }}</h5>

                                                <div class="form-group">
                                                    <label for="question_text_{{ $i }}">Question Text</label>
                                                    <textarea name="questions[{{ $i }}][question_text]" id="question_text_{{ $i }}" class="form-control" rows="3" required></textarea>
                                                </div>

                                                <input type="hidden" name="questions[{{ $i }}][question_type]" value="true_false">

                                                <div class="form-group">
                                                    <label>Correct Answer</label>
                                                    <select name="questions[{{ $i }}][correct_answer]" class="form-control" required>
                                                        <option value="true">True</option>
                                                        <option value="false">False</option>
                                                        <option value="not_given">Not Given</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                @endif

                                <button type="submit" class="btn btn-primary">{{ isset($assignment) ? 'Update' : 'Submit' }}</button>
                            </form>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
    <script src="{{ asset('admin/assets/build/ckeditor.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select the textarea where you want to apply CKEditor
            ClassicEditor
                .create(document.querySelector('#description'), {
                    // Configuration options
                })
                .then(editor => {
                    window.editor = editor; // Store editor instance for potential future use
                })
                .catch(error => {
                    console.error('Error occurred in initializing the editor:', error);
                });
        });
    </script>
@endsection


{{-- @extends('admin.layouts.layout-admin')

@section('content')
<div class="py-3 py-lg-4">
    <div class="row">
        <div class="col-lg-6">
            <h4 class="page-title mb-0">TRUE/FALSE/NOT GIVEN</h4>
        </div>
        <div class="col-lg-6">
            <div class="d-none d-lg-block">
                <ol class="breadcrumb m-0 float-end">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">New Question For True/False/Not Given Type of Assignment</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="py-3 py-lg-4">
    <div class="row">
        <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('create.assignemnt') }}">
                <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Assignment - New Question For True/False/Not Given Type</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="p-2">

                            <form action="{{ route('storeTruefalseType') }}" method="POST">
                                @csrf

                                <!-- Assignment Fields -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Assignment Details</h5>

                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" class="form-control"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="isEnable">Enable</label>
                                            <select name="isEnable" id="isEnable" class="form-control" required>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="show_detailed_feedback">Show Detailed Feedback</label>
                                            <input type="checkbox" name="show_detailed_feedback" id="show_detailed_feedback" class="form-check-input">
                                        </div>
                                    </div>
                                </div>

                                <!-- True/False/Not Given Questions -->
                                @for ($i = 0; $i < $quantity; $i++)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Question {{ $i + 1 }}</h5>

                                            <div class="form-group">
                                                <label for="question_text_{{ $i }}">Question Text</label>
                                                <textarea name="questions[{{ $i }}][question_text]" id="question_text_{{ $i }}" class="form-control" rows="3" required></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>Correct Answer</label>
                                                <select name="questions[{{ $i }}][correct_answer]" class="form-control" required>
                                                    <option value="true">True</option>
                                                    <option value="false">False</option>
                                                    <option value="not_given">Not Given</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endfor

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>

                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
@endsection --}}
