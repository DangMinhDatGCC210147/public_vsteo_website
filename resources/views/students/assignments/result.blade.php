@extends('students.layouts.layout-student')

@section('content')
    <div class="container-fluid">
        <div class="card p-3">
            <div class="row text-dark card-header navbar" id="navbar">
                <div class="col-md-1">
                    <button class="btn btn-warning d-flex justify-content-center" id="theme-mode"><i
                            class="bx bx-moon font-size-18"></i></button>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-light"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            <h1 class="mb-4 mt-3">{{ $assignment->title }} - Result</h1>
            <div class="row">
                <div class="col-md-6" style="height: 38vw; overflow-y: auto; text-align: justify;">
                    <p>{!! nl2br($assignment->description) !!}</p>
                </div>
                <div class="col-md-6" style="height: 38vw; overflow-y: auto; text-align: justify;">
                    <p class="mb-4">You got {{ $correctAnswers }} out of {{ $totalQuestions }} correct.</p>
                    @if ($assignment->show_detailed_feedback)
                        <h3>Detailed Result</h3>
                        @foreach ($answers as $index => $answer)
                            <div class="mb-3 p-3 border rounded">
                                <label><strong>Question {{ $index + 1 }}:
                                        {{ $answer->question->question_text }}</strong></label>
                                @switch($answer->question->question_type)
                                    @case('multiple_choice')
                                        @foreach ($answer->question->multipleChoiceOptions as $option)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="question_{{ $answer->question->id }}" value="{{ $option->option_text }}"
                                                    id="option_{{ $option->id }}" disabled
                                                    {{ $option->option_text == $answer->answer_text ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="option_{{ $option->id }}">{{ $option->option_text }}
                                                    @if ($option->is_correct)
                                                        <span class="text-success">(Correct)</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    @break

                                    @case('true_false')
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="question_{{ $answer->question->id }}" value="true"
                                                id="true_{{ $answer->question->id }}" disabled
                                                {{ $answer->answer_text == 'true' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="true_{{ $answer->question->id }}">True
                                                @if ($answer->question->trueFalse->correct_answer == 'true')
                                                    <span class="text-success">(Correct)</span>
                                                @endif
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="question_{{ $answer->question->id }}" value="false"
                                                id="false_{{ $answer->question->id }}" disabled
                                                {{ $answer->answer_text == 'false' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="false_{{ $answer->question->id }}">False
                                                @if ($answer->question->trueFalse->correct_answer == 'false')
                                                    <span class="text-success">(Correct)</span>
                                                @endif
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="question_{{ $answer->question->id }}" value="not_given"
                                                id="not_given_{{ $answer->question->id }}" disabled
                                                {{ $answer->answer_text == 'not_given' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="not_given_{{ $answer->question->id }}">Not Given
                                                @if ($answer->question->trueFalse->correct_answer == 'not_given')
                                                    <span class="text-success">(Correct)</span>
                                                @endif
                                            </label>
                                        </div>
                                    @break

                                    @case('fill_in_the_blank')
                                        @foreach ($answer->question->fillInTheBlanks as $index => $blank)
                                            <input type="text" name="question_{{ $answer->question->id }}[{{ $index }}]"
                                                class="form-control" value="{{ $answer->answer_text[$index] ?? '' }}" disabled>
                                            <p class="mt-2">Correct Answer: <span
                                                    class="text-success">{{ $blank->correct_answer }}</span></p>
                                        @endforeach
                                    @break

                                    @case('matching_headline')
                                        @foreach ($answer->question->matchingHeadlines as $index => $headline)
                                            @if (!empty($headline->match_text))
                                                <div class="mb-2">
                                                    <div class="d-flex">
                                                        <label><strong>{{ $headline->match_text }} : </strong></label>
                                                        @if (
                                                            ($answer->answer_text[$headline->match_text] ?? '') &&
                                                                ($answer->answer_text[$headline->match_text] ?? '') != $headline->headline)
                                                            <label for=""><strong class="text-danger">
                                                                    (Incorrect)</strong></label>
                                                        @endif
                                                    </div>
                                                    <select name="question_{{ $answer->question->id }}[{{ $index }}]"
                                                        class="form-control" disabled>
                                                        @foreach ($answer->question->matchingHeadlines->sortBy('headline') as $option)
                                                            <option value="{{ $option->headline }}"
                                                                {{ $option->headline == ($answer->answer_text[$headline->match_text] ?? '') ? 'selected' : '' }}>
                                                                {{ $option->headline }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <p class="mt-2">Correct Answer: <span
                                                            class="text-success">{{ $headline->headline }}</span></p>
                                                </div>
                                            @endif
                                        @endforeach
                                    @break
                                @endswitch
                                @if ($answer->question->question_type !== 'matching_headline' || $answer->question->question_type !== 'true_false')
                                    <p class="mt-2">Your Answer: <span
                                            class="{{ $answer->is_correct ? 'text-success' : 'text-danger' }}">{{ is_array($answer->answer_text) ? implode(', ', $answer->answer_text) : $answer->answer_text }}</span>
                                    </p>
                                @endif
                                @if ($answer->question->question_type == 'true_false')
                                    <p class="mt-2">Your Answer:
                                        <span class="{{ $answer->is_correct ? 'text-success' : 'text-danger' }}">
                                            @switch($answer->answer_text)
                                                @case('true')
                                                    True
                                                @break

                                                @case('false')
                                                    False
                                                @break

                                                @default
                                                    Not Given
                                            @endswitch
                                        </span>
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('./students/assets/js/record_speaking.js') }}"></script>
@endsection
