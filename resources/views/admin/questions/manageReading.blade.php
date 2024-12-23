@extends('admin.layouts.layout-admin')

@section('content')
    <div class="modal fade" id="scrollable-modal" tabindex="-1" role="dialog" aria-labelledby="scrollableModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollableModalTitle">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas
                        eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue
                        laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl
                        consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas
                        eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue
                        laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl
                        consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas
                        eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue
                        laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl
                        consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas
                        eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue
                        laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl
                        consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas
                        eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue
                        laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl
                        consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas
                        eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue
                        laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl
                        consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    @if ($passages == null)
        <div class="row navigation pt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title">Reading Skill - {{ $test->test_name }}</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Back to Skill Parts</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
            <div class="sub_nav">
                <div class="col-6">
                    <a href="{{ route('testSkills.show', $test->slug) }}" class="btn btn-secondary mb-4">
                        <i class="mdi mdi-arrow-left-bold"></i> Return to previous page
                    </a>
                </div>
            </div>
        </div>
        <div class="container py-4">
            <div class="card shadow">
                <div class="card-body p-5">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#scrollable-modal">Instruction</button>
                    <form
                        action="{{ route('reading.questions.store', ['test_slug' => $test_slug, 'skill_id' => $skill->id]) }}"
                        method="POST">
                        @csrf
                        @foreach (range(1, 4) as $part)
                            <div class="mb-4">
                                <h3 class="d-flex justify-content-center">PASSAGE - PART {{ $part }}</h3>
                                <textarea name="passages[{{ $part }}]" id="editor{{ $part }}" class="form-control mb-3" rows="7"
                                    placeholder="Enter passage text for part {{ $part }}"></textarea>
                                <h4>Questions {{ ($part - 1) * 10 + 1 }} to {{ $part * 10 }}</h4>
                                @for ($q = 1; $q <= 10; $q++)
                                    <div class="mb-3">
                                        <label class="form-label">Question {{ ($part - 1) * 10 + $q }}</label>
                                        <input type="text" name="questions[{{ ($part - 1) * 10 + $q }}][text]"
                                            class="form-control mb-2">
                                        <div class="row">
                                            @foreach (range(1, 4) as $option)
                                                <div class="col-md-12">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input justify-content-center"
                                                            type="radio"
                                                            name="questions[{{ ($part - 1) * 10 + $q }}][correct_answer]"
                                                            value="{{ $option }}"
                                                            id="defaultCheck{{ ($part - 1) * 10 + $q }}-{{ $option }}">
                                                        {{-- <label class="form-check-label"
                                                            for="defaultCheck{{ ($part - 1) * 10 + $q }}-{{ $option }}">
                                                            Option {{ $option }}
                                                        </label> --}}
                                                        <input type="text"
                                                            name="questions[{{ ($part - 1) * 10 + $q }}][options][{{ $option }}]"
                                                            class="form-control" placeholder="Option {{ $option }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        @endforeach
                        <div class="buttons d-flex justify-content-center"><button type="submit"
                                class="btn btn-warning">Save</button></div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="row navigation pt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title">Reading Skill - {{ $test_slug->test_name }}</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Back to Skill Parts</a></li>
                    <li class="breadcrumb-item active">Edit Question</li>
                </ol>
            </div>
            <div class="sub_nav">
                <div class="col-6">
                    <a href="{{ route('testSkills.show', $test_slug) }}" class="btn btn-secondary mb-4">
                        <i class="mdi mdi-arrow-left-bold"></i> Return to previous page
                    </a>
                </div>
            </div>
        </div>
        <div class="container py-4">
            <div class="card shadow">
                <div class="card-body p-5">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#scrollable-modal">Instruction</button>
                    <form
                        action="{{ route('reading.questions.update', ['test_slug' => $test_slug, 'skill_slug' => $skill_slug]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        @foreach ($passages as $partIndex => $part)
                            <div class="mb-4">
                                <div class="passage d-flex justify-content-center">
                                    <h3>PASSAGE - PART {{ $partIndex + 1 }}</h3>
                                </div>
                                <textarea name="passages[{{ $part->id }}]" id="editor{{ $part->id }}" class="ckeditor form-control mb-3"
                                    rows="10" placeholder="Enter passage text for part {{ $partIndex + 1 }}">{{ $part->reading_audio_file }}</textarea>
                                <h4>Questions {{ $partIndex * 10 + 1 }} to {{ ($partIndex + 1) * 10 }}</h4>
                                @foreach ($questions->where('reading_audio_id', $part->id) as $question)
                                    <div class="mb-3">
                                        <input type="hidden"
                                            name="questions[{{ $part->id }}][{{ $question->id }}][id]"
                                            value="{{ $question->id }}">
                                        <label class="form-label">Question
                                            {{ $loop->iteration + $partIndex * 10 }}</label>
                                        <input type="text"
                                            name="questions[{{ $part->id }}][{{ $question->id }}][text]"
                                            class="form-control mb-2" value="{{ $question->question_text }}">
                                        <div class="row">
                                            @foreach ($question->options as $option)
                                                <div class="col-md-12">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio"
                                                            name="questions[{{ $part->id }}][{{ $question->id }}][correct_answer]"
                                                            value="{{ $option->id }}"
                                                            id="defaultCheck{{ $loop->parent->iteration + $partIndex * 10 }}-{{ $option->id }}"
                                                            {{ $option->correct_answer == 1 ? 'checked' : '' }}>
                                                        </label>
                                                        <input type="text"
                                                            name="questions[{{ $part->id }}][{{ $question->id }}][options][{{ $option->id }}]"
                                                            class="form-control"
                                                            placeholder="Option {{ $loop->iteration }}"
                                                            value="{{ $option->option_text }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                @if ($questions->where('reading_audio_id', $part->id)->isEmpty())
                                    @for ($i = $partIndex * 10 + 1; $i <= ($partIndex + 1) * 10; $i++)
                                        <div class="mb-3">
                                            <label class="form-label">Question {{ $i }}</label>
                                            <input type="text"
                                                name="questions[{{ $part->id }}][{{ $i }}][text]"
                                                class="form-control mb-2">
                                            <div class="row">
                                                @for ($j = 1; $j <= 4; $j++)
                                                    <div class="col-md-6">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="radio"
                                                                name="questions[{{ $part->id }}][{{ $i }}][correct_answer]"
                                                                value="{{ $j }}"
                                                                id="defaultCheck{{ $i }}-{{ $j }}">
                                                            <label class="form-check-label"
                                                                for="defaultCheck{{ $i }}-{{ $j }}">
                                                                Option {{ $j }}
                                                            </label>
                                                            <input type="text"
                                                                name="questions[{{ $part->id }}][{{ $i }}][options][{{ $j }}]"
                                                                class="form-control"
                                                                placeholder="Option {{ $j }}">
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        @endforeach
                        <div class="buttons d-flex justify-content-center"><button type="submit"
                                class="btn btn-warning">Save Changes</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <script src="{{ asset('admin/assets/build/ckeditor.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            for (let i = 1; i <= 4; i++) {
                ClassicEditor
                    .create(document.querySelector(`#editor${i}`), {
                        // Configuration options
                    })
                    .then(editor => {
                        console.log(`Editor for part ${i} was initialized`, editor);
                    })
                    .catch(error => {
                        console.error(`Error occurred in initializing editor for part ${i}:`, error);
                    });
            }
        });
    </script>
@endsection
