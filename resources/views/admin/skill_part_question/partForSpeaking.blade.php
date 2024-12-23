@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">SPEAKING SKILL</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">New Question For {{ str_replace('_', ' ', $partName) }} of
                            {{ $skillName }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('questionBank.speaking') }}">
                    <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Speaking Skill - New Question For {{ str_replace('_', ' ', $partName) }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2">
                                @if ($partName == 'Part_1')
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Hướng dẫn tạo câu hỏi Speaking cho form</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Topic: </strong> Tại ô nhập này, giảng viên sẽ nhập chủ đề của phần nói</p>
                                                <p><strong>Question: </strong> Tại đây các ô questions, giảng viên nhập các câu hỏi của topic đã nhập</p>
                                                <p><strong>Nút Save/Save changes: </strong>Khi đã thực hiện nhập các yêu cầu trên đầy đủ sẽ bấm vào nút Save/Save changes để lưu</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-light mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Hướng dẫn tạo câu hỏi
                                </button>
                                    @if (isset($speaking))
                                        <form action="{{ route('updateQuestionSpeaking') }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug" value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h3>Part 1</h3>
                                                </div>
                                                <div class="card-body">
                                                    @foreach ($questions as $index => $question)
                                                        <div class="form-group mt-3">
                                                            <label for="question{{ $index }}">Topic {{ $question->question_number }}:</label>
                                                            <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                                                            <input type="text" id="question{{ $index }}"
                                                                name="questions[{{ $index }}][text]" class="form-control mt-2"
                                                                placeholder="Enter question {{ $index + 1 }}" value="{{ old('questions.' . $index . '.text', $question->question_text ?? '') }}">
                                                            <div class="mt-2">
                                                                @foreach ($question->options as $optionIndex => $option)
                                                                    <div class="mb-1 d-flex mt-4">
                                                                        <input type="hidden"
                                                                            name="questions[{{ $index }}][options][{{ $optionIndex }}][id]"
                                                                            value="{{ $option->id }}">
                                                                        <div class="col-md-1">
                                                                            <label class="form-label">Question{{ $optionIndex + 1 }}:</label>
                                                                        </div>
                                                                        <div class="col-md-11">
                                                                            <input type="text"
                                                                                name="questions[{{ $index }}][options][{{ $optionIndex }}][text]"
                                                                                class="form-control"
                                                                                placeholder="Option {{ $optionIndex + 1 }}"
                                                                                value="{{ old('questions.' . $index . '.options.' . $optionIndex . '.text', $option->option_text ?? '') }}">
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save changes</button>
                                        </form>
                                    @else
                                        <form action="{{ route('storeQuestionSpeaking') }}" method="post">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName"
                                                value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName"
                                                value="{{ $skillName }}">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h3>Part 1</h3>
                                                </div>
                                                <div class="card-body">
                                                    @for ($i = 1; $i <= 2; $i++)
                                                        <div class="mb-3">
                                                            <label class="form-label">Topic
                                                                {{ $i }}:</label>
                                                            <input type="text" name="part1_question_{{ $i }}"
                                                                class="form-control" required>
                                                            @for ($j = 1; $j <= 3; $j++)
                                                                <div class="mb-1 d-flex mt-4">
                                                                    <div class="col-md-1"><label class="form-label">Question
                                                                            {{ $j }}:</label></div>
                                                                    <div class="col-md-11">
                                                                        <input type="text"
                                                                            name="part1_question_{{ $i }}_option_{{ $j }}"
                                                                            class="form-control" required>
                                                                    </div>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    @endif
                                @elseif ($partName == 'Part_2')
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Hướng dẫn tạo câu hỏi Speaking cho form</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Requirement: </strong> Tại ô này, giảng viên sẽ nhập đầy đủ yêu cầu của Part 2</p>
                                                <p><strong>Nút Save/Save changes: </strong>Khi đã thực hiện nhập các yêu cầu trên đầy đủ sẽ bấm vào nút Save/Save changes để lưu</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-light mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Hướng dẫn tạo câu hỏi
                                </button>
                                    @if (isset($speaking))
                                        <form action="{{ route('updateQuestionSpeaking') }}" method="post"
                                            id="questionForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug"
                                                value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName"
                                                value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName"
                                                value="{{ $skillName }}">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h3>Part 2</h3>
                                                </div>
                                                <div class="card-body">
                                                    <label class="form-label">Requirement 3: </label>
                                                    <textarea name="passage" class="form-control" id="passage-editor" rows="6"
                                                        placeholder="Enter requirement here">{{ old('questions', $questions->question_text ?? '') }}</textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save changes</button>
                                        </form>
                                    @else
                                        <form action="{{ route('storeQuestionSpeaking') }}" method="post" id="questionForm">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName"
                                                value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName"
                                                value="{{ $skillName }}">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h3>Part 2</h3>
                                                </div>
                                                <div class="card-body">
                                                    <label class="form-label">Requirement 3: </label>
                                                    <textarea name="passage" class="form-control passage-editor" id="passage-editor" rows="6"></textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    @endif
                                @else
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Hướng dẫn tạo câu hỏi Speaking cho form</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Topic: </strong> Tại ô nhập này, giảng viên sẽ nhập chủ đề của phần nói.</p>
                                                <p><strong>Upload Image: </strong>Tại đây, giảng viên sẽ chọn hình ảnh diagram cho bài nói phần 3.</p>
                                                <p><strong>Question: </strong> Tại đây các ô questions, giảng viên nhập các câu hỏi follow up.</p>
                                                <p><strong>Nút Save/Save changes: </strong>Khi đã thực hiện nhập các yêu cầu trên đầy đủ sẽ bấm vào nút Save/Save changes để lưu.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-light mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Hướng dẫn tạo câu hỏi
                                </button>
                                    @if (isset($speaking))
                                        <form action="{{ route('updateQuestionSpeaking') }}" method="POST"
                                            enctype="multipart/form-data" id="questionForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug" value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h3>Part 3</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Topic</label>
                                                        <input type="text" name="part3_question" class="form-control"
                                                            value="{{ old('part3_question', $questions[0]->question_text ?? '') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Upload Image</label>
                                                        <input type="file" name="part3_image" class="form-control"
                                                            accept="image/*">
                                                        @if ($passage && $passage->reading_audio_file)
                                                            <img src="{{ asset('storage/' . $passage->reading_audio_file) }}" alt="Current Image" class="img-thumbnail mt-2" style="max-width: 400px;">
                                                            <input type="hidden" name="current_image" value="{{ $passage->reading_audio_file }}">
                                                        @endif
                                                    </div>
                                                    @foreach ($questions as $index => $question)
                                                        @php
                                                            $optionIndex = 1;
                                                        @endphp
                                                        @foreach ($question->options as $option)
                                                            <div class="mt-2 row">
                                                                <div class="col-md-1"><label class="form-label">Question {{ $optionIndex }}: </label></div>
                                                                <div class="col md-11">
                                                                    <input type="text" name="part3_option_{{ $optionIndex }}" class="form-control" value="{{ old('part3_option_' . $optionIndex, $option->option_text ?? '') }}" required>
                                                                </div>
                                                            </div>
                                                            @php
                                                                $optionIndex++;
                                                            @endphp
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                        </form>
                                    @else
                                        <form action="{{ route('storeQuestionSpeaking') }}" method="POST"
                                            enctype="multipart/form-data" id="questionForm">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h3>Part 3</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Topic</label>
                                                        <input type="text" name="part3_question" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Upload Image</label>
                                                        <input type="file" name="part3_image" class="form-control" accept="image/*" required>
                                                    </div>
                                                    @for ($k = 1; $k <= 3; $k++)
                                                        <div class="mb-1">
                                                            <label class="form-label">Question {{ $k }}: </label>
                                                            <input type="text" name="part3_option_{{ $k }}"
                                                                class="form-control" required>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
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
            const form = document.getElementById('questionForm');
            // Initialize CKEditor
            ClassicEditor
                .create(document.querySelector('#passage-editor'), {
                    removePlugins: ['FontColor']
                    // Configuration options
                })
                .catch(error => {
                    console.error('Error occurred in initializing the editor:', error);
                });
        });
    </script>
@endsection
