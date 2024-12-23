@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">WRITING SKILL</h4>
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
            <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('questionBank.writing') }}">
                    <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Writing Skill - New Question For {{ str_replace('_', ' ', $partName) }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2">
                                @if ($partName ==  'Part_1')
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Hướng dẫn tạo câu hỏi Writing cho form</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Requirement: </strong> Tại ô nhập này, nội dung mặc định sẽ là "You should spend 20 minutes for this task". Nếu giảng viên có yêu cầu đặt biệt hơn có thể thay đổi cho phù hợp.</p>
                                                <p><strong>Passage: </strong> Tại đây, giảng viên nhập nội dung của đề bài/ bức thư, ...</p>
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
                                    @if (isset($questions))
                                        <form action="{{ route('updateQuestionWriting') }}" method="POST" id="questionForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug" value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group mt-3">
                                                <label for="question" class="mb-3">Requirement 1:</label>
                                                <input type="text" id="question" name="question" class="form-control" placeholder="Enter requirement here" value="{{ old('questions', $questions->question_text ?? '') }}" required>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="passage{{ $partName }}" class="mb-3">Passage 1:</label>
                                                <textarea id="editor{{ $partName }}" class="form-control" name="passage" rows="6" placeholder="Enter passage here">{{ old('passage', $passage->reading_audio_file ?? '') }}</textarea>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                        </form>
                                    @else
                                        <form id="questionForm" action="{{ route('storeQuestionWriting') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group mt-3">
                                                <label for="question" class="mb-3">Requirement 1:</label>
                                                <input type="text" id="question" name="question" class="form-control" placeholder="Enter requirement here" value="You should spend 20 minutes for this task" required>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="passage{{ $partName }}" class="mb-3">Passage 1:</label>
                                                <textarea id="editor{{ $partName }}" class="form-control" name="passage" rows="6" placeholder="Enter passage here"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    @endif
                                @else
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Hướng dẫn tạo câu hỏi Writing cho form</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Requirement: </strong> Tại ô nhập này, nội dung mặc định sẽ là "You should spend 40 minutes for this task". Nếu giảng viên có yêu cầu đặt biệt hơn có thể thay đổi cho phù hợp.</p>
                                                <p><strong>Passage: </strong> Tại đây, giảng viên nhập nội dung của đề bài/ bức thư, ...</p>
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
                                    @if (isset($questions))
                                        <form action="{{ route('updateQuestionWriting') }}" method="POST" id="questionForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug" value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group mt-3">
                                                <label for="question" class="mb-3">Requirement 2:</label>
                                                <input type="text" id="question" name="question" class="form-control" placeholder="Enter requirement here" value="{{ old('questions', $questions->question_text ?? 'You should spend about 40 minutes on this task.') }}" required>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="passage{{ $partName }}" class="mb-3">Passage 2:</label>
                                                <textarea id="editor{{ $partName }}" class="form-control" name="passage" rows="6" placeholder="Enter passage here">{{ old('passage', $passage->reading_audio_file ?? '') }}</textarea>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                        </form>
                                    @else
                                        <form id="questionForm" action="{{ route('storeQuestionWriting') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group mt-3">
                                                <label for="question" class="mb-3">Requirement 2:</label>
                                                <input type="text" id="question" name="question" class="form-control" placeholder="Enter requirement here" required value="You should spend about 40 minutes on this task.">
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="passage{{ $partName }}" class="mb-3">Passage 2:</label>
                                                <textarea id="editor{{ $partName }}" class="form-control" name="passage" rows="6" placeholder="Enter passage here"></textarea>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Save</button>
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
            const editors = {};

            // Initialize CKEditor
            document.querySelectorAll('.form-control[name="passage"]').forEach((textarea, index) => {
                ClassicEditor
                    .create(textarea, {
                        removePlugins: ['FontColor']
                        // Configuration options
                    })
                    .then(editor => {
                        editors[index] = editor;
                    })
                    .catch(error => {
                        console.error('Error occurred in initializing the editor:', error);
                    });
            });
        });
    </script>
@endsection
