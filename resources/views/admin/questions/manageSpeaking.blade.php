@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">
                    <h2>Speaking Skill - {{ $test_slug->test_name }}</h2>
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
            <div class="col-lg-6">
                <a href="{{ route('testSkills.show', $test_slug->slug) }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page
                </a>
            </div>
        </div>
    </div>
    @if ($passages == null)
        <div class="container">
            <h1>Create Speaking Test</h1>
            <form
                action="{{ route('speaking.questions.store', ['test_slug' => $test_slug, 'skill_id' => $skill_slug->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Part 1 --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h3>Part 1</h3>
                    </div>
                    <div class="card-body">
                        @for ($i = 1; $i <= 2; $i++)
                            <div class="mb-3">
                                <label class="form-label">Requirement {{ $i }}</label>
                                <input type="text" name="part1_question_{{ $i }}" class="form-control"
                                    required>
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

                {{-- Part 2 --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h3>Part 2</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Requirement</label>
                            <input type="text" name="part2_question" class="form-control" required>
                        </div>
                        <textarea name="part2_text" class="form-control" rows="6" required></textarea>
                    </div>
                </div>

                {{-- Part 3 --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h3>Part 3</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Requirement</label>
                            <input type="text" name="part3_question" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Image</label>
                            <input type="file" name="part3_image" class="form-control" accept="image/*" required>
                        </div>
                        @for ($k = 1; $k <= 3; $k++)
                            <div class="mb-1">
                                <label class="form-label">Question {{ $k }}: </label>
                                <input type="text" name="part3_option_{{ $k }}" class="form-control" required>
                            </div>
                        @endfor
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    @else
        <div class="container">
            <h1>Edit Speaking Test</h1>
            <form
                action="{{ route('speaking.questions.update', ['test_slug' => $test_slug, 'skill_slug' => $skill_slug]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @foreach ($questions->where('part_name', 'Part_1') as $index => $question)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3>Part 1 - Question {{ $index + 1 }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Requirement {{ $index + 1 }}</label>
                                <input type="text" name="part1_question_{{ $index + 1 }}" class="form-control"
                                    value="{{ $question->question_text }}" required>
                                @foreach ($question->options as $j => $option)
                                    <div class="mb-1 d-flex mt-4">
                                        <div class="col-md-1"><label class="form-label">Question {{ $j + 1 }}:</label>
                                        </div>
                                        <div class="col-md-11">
                                            <input type="text"
                                                name="part1_question_{{ $index + 1 }}_option_{{ $j + 1 }}"
                                                class="form-control" value="{{ $option->option_text }}" required>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="card mb-3">
                    <div class="card-header">
                        <h3>Part 2</h3>
                    </div>
                    <div class="card-body">
                        <label class="form-label">Requirement 3: </label>
                        <textarea name="part2_text" class="form-control" rows="6" required>{{ $questions[2]->question_text }}</textarea>
                    </div>
                </div>
                {{-- Part 3 --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h3>Part 3</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Follow Up Question</label>
                            <input type="text" name="part3_question" class="form-control"
                                value="{{ $questions[3]->question_text }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div class="d-flex justify-content-center mb-5"><img src="{{ asset('storage/' . $passages[0]->reading_audio_file) }}" alt="Uploaded Image"
                                    style="max-height: 200px;"></div>
                            <label class="form-label">Change file</label>
                            <input type="file" name="part3_image" class="form-control" accept="image/*">
                            <div class="invalid-feedback">
                                Please choose a username.
                            </div>
                        </div>
                        @foreach ($questions[3]->options as $k => $option)
                            <div class="mb-1">
                                <div class="row">
                                    <div class="col-lg-1">
                                        <label class="form-label">Question {{ $k + 1 }}: </label>
                                    </div>
                                    <div class="col-lg-11">
                                        <input type="text" name="part3_option_{{ $k + 1 }}" class="form-control"
                                            value="{{ $option->option_text }}" required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    @endif
@endsection
