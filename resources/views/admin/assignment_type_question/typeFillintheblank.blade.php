@extends('admin.layouts.layout-admin')

@section('content')
<div class="py-3 py-lg-4">
    <div class="row">
        <div class="col-lg-6">
            <h4 class="page-title mb-0">FILL IN THE BLANK</h4>
        </div>
        <div class="col-lg-6">
            <div class="d-none d-lg-block">
                <ol class="breadcrumb m-0 float-end">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">{{ isset($assignment) ? 'Edit' : 'New' }} Question For Fill In The Blank Type of Assignment</li>
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
                <h3>Assignment - {{ isset($assignment) ? 'Edit' : 'New' }} Question For Fill In The Blank Type</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="p-2">
                            <form action="{{ isset($assignment) ? route('updateAssignment', $assignment->id) : route('storeFillintheblankType') }}" method="POST">
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
                                            <label for="show_detailed_feedback">Show answer after submit: </label>
                                            <input type="hidden" name="show_detailed_feedback" value="0">
                                            <input type="checkbox" name="show_detailed_feedback" id="show_detailed_feedback" class="form-check-input" value="1" {{ isset($assignment) && $assignment->show_detailed_feedback ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fill In The Blank Questions -->
                                @if (isset($assignment))
                                    @foreach ($questions as $i => $question)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">Question {{ $i + 1 }}</h5>

                                                <div class="form-group mt-3">
                                                    <textarea name="questions[{{ $i }}][question_text]" id="question_text_{{ $i }}" class="form-control" rows="3" required>{{ $question->question_text }}</textarea>
                                                </div>

                                                <input type="hidden" name="questions[{{ $i }}][question_type]" value="fill_in_the_blank">

                                                <div class="form-group mt-3">
                                                    <label>Blanks and Correct Answers</label>
                                                    <div id="blanks-container-{{ $i }}" class="blanks-container">
                                                        @foreach ($question->fillInTheBlanks as $j => $blank)
                                                            <div class="form-group d-flex align-items-center mb-2 blank-row">
                                                                <input type="number" name="questions[{{ $i }}][blanks][{{ $j }}][blank_position]" class="form-control mr-2 blank-position" placeholder="Pos" style="width: 60px;" value="{{ $blank->blank_position }}" required readonly>
                                                                <input type="text" name="questions[{{ $i }}][blanks][{{ $j }}][correct_answer]" class="form-control mr-2" placeholder="Correct Answer" style="flex: 1;" value="{{ $blank->correct_answer }}" required>
                                                                <button type="button" class="btn btn-danger btn-sm ml-2 remove-blank">Remove</button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" class="btn btn-secondary add-blank" data-index="{{ $i }}">Add Blank</button>
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

                                                <input type="hidden" name="questions[{{ $i }}][question_type]" value="fill_in_the_blank">

                                                <div class="form-group">
                                                    <label>Blanks and Correct Answers</label>
                                                    <div id="blanks-container-{{ $i }}" class="blanks-container">
                                                        <div class="form-group d-flex align-items-center mb-2 blank-row">
                                                            <input type="number" name="questions[{{ $i }}][blanks][0][blank_position]" class="form-control mr-2 blank-position" placeholder="Pos" style="width: 60px;" value="1" required readonly>
                                                            <input type="text" name="questions[{{ $i }}][blanks][0][correct_answer]" class="form-control mr-2" placeholder="Correct Answer" style="flex: 1;" required>
                                                            <button type="button" class="btn btn-danger btn-sm ml-2 remove-blank">Remove</button>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-secondary add-blank" data-index="{{ $i }}">Add Blank</button>
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

            const addBlankButtons = document.querySelectorAll('.add-blank');

            addBlankButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const index = button.getAttribute('data-index');
                    const container = document.getElementById('blanks-container-' + index);
                    const currentCount = container.children.length;
                    const newBlank = document.createElement('div');
                    newBlank.classList.add('form-group', 'd-flex', 'align-items-center', 'mb-2', 'blank-row');
                    newBlank.innerHTML = `
                        <input type="number" name="questions[${index}][blanks][${currentCount}][blank_position]" class="form-control mr-2 blank-position" placeholder="Pos" style="width: 60px;" value="${currentCount + 1}" required readonly>
                        <input type="text" name="questions[${index}][blanks][${currentCount}][correct_answer]" class="form-control mr-2" placeholder="Correct Answer" style="flex: 1;" required>
                        <button type="button" class="btn btn-danger btn-sm ml-2 remove-blank">Remove</button>
                    `;
                    container.appendChild(newBlank);
                    addRemoveEvent(newBlank.querySelector('.remove-blank'));
                });
            });

            function addRemoveEvent(button) {
                button.addEventListener('click', function() {
                    const container = button.closest('.blank-row').parentElement;
                    button.closest('.blank-row').remove();
                    updateBlankIndexes(container);
                });
            }

            document.querySelectorAll('.remove-blank').forEach(addRemoveEvent);

            function updateBlankIndexes(container) {
                container.querySelectorAll('.blank-row').forEach((row, index) => {
                    row.querySelector('.blank-position').value = index + 1;
                    row.querySelector('.blank-position').name = `questions[${container.id.split('-').pop()}][blanks][${index}][blank_position]`;
                    row.querySelector('input[type="text"]').name = `questions[${container.id.split('-').pop()}][blanks][${index}][correct_answer]`;
                });
            }
        });
    </script>
@endsection
