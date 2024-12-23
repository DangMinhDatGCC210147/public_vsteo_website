@extends('students.layouts.layout-student')

@section('content')
    @php
        $badgeColors = [
            'Listening' => 'bg-primary',
            'Speaking' => 'bg-danger',
            'Reading' => 'bg-success',
            'Writing' => 'bg-secondary',
        ];
    @endphp
    <div data-test-id="{{ $test->id }}" id="testContainer" hidden></div>
    <div class="px-3">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="card">
                <div class="row text-dark card-header navbar" id="navbar">
                    <div class="col-md-1">
                        <button class="btn btn-warning d-flex justify-content-center" id="theme-mode"><i
                                class="bx bx-moon font-size-18"></i></button>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2>Timer:
                            <span class="badge bg-primary" id="skill-timer" style="display: inline;">
                                00:00
                            </span>
                        </h2>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="badge bg-primary">
                            <span id="answered-count">Số câu đã hoàn thành: 0/0</span>
                        </div>
                        <button class="btn btn-warning" id="submitTestButton" data-test-id="{{ $test->id }}"
                            style="font-size: 16px"><strong>Nộp bài</strong></button>
                    </div>
                </div>
                <div class="m-2 mb-5">
                    <div class="row" id="content-row">
                        <div class="col-md-12 overflow-auto border-style content-area" style="height: 100%;"
                            id="content-area">
                            @foreach ($testParts as $testPart)
                                <div class="skill-content" data-skill-id="{{ $testPart->testSkill->id }}"
                                    data-part-id="{{ $testPart->testSkill->part_name }}" style="display: none;">
                                    @foreach ($testPart->testSkill->readingsAudios as $audio)
                                        <div>
                                            @if ($testPart->testSkill->skill_name == 'Writing')
                                                @php
                                                    $questionForPart = $testPart->testSkill->questions->firstWhere(
                                                        'part_name',
                                                        $audio->part_name,
                                                    );
                                                @endphp
                                                @if ($questionForPart)
                                                    <strong>
                                                        <p>Question {{ $questionForPart->question_number }}:
                                                            {!! nl2br($questionForPart->question_text) !!}</p>
                                                    </strong>
                                                @endif
                                            @endif

                                            @if ($audio->isAudio())
                                                <style>
                                                    audio::-webkit-media-controls-volume-control-container,
                                                    audio::-webkit-media-controls-mute-button,
                                                    audio::-webkit-media-controls-volume-slider {
                                                        display: none !important;
                                                    }

                                                    .mejs-container {
                                                        width: 100% !important;
                                                        /* Set width to 100% to make it full width */
                                                    }

                                                    .mejs-controls {
                                                        width: 100% !important;
                                                        /* Set controls width to 100% */
                                                    }
                                                </style>
                                                <audio controls controlsList="nodownload"
                                                    id="audioPlayer-{{ $audio->id }}">
                                                    <source src="{{ asset('storage/' . $audio->reading_audio_file) }}"
                                                        type="audio/mpeg" id="audioPlayer">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            @elseif ($audio->isImage())
                                                <div class="image d-flex justify-content-center">
                                                    <img src="{{ asset('storage/' . $audio->reading_audio_file) }}"
                                                        alt="Reading Image" style="max-width: 500px; height: auto;"
                                                        class="img-fluid">
                                                </div>
                                            @elseif ($audio->isText())
                                                <p>{!! nl2br($audio->reading_audio_file) !!}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                            <div class="card notification text-bg-danger mb-3" id="notification"
                                style="display: none; max-width: 100%;">
                                <div class="card-header text-dark">
                                    <span>CHÚ Ý:</span>
                                </div>
                                <div class="card-body">
                                    <blockquote class="blockquote mb-0">
                                        <span>BÀI NÓI ĐANG ĐƯỢC THU ÂM TRỰC TIẾP, TRONG QUÁ TRÌNH THU ÂM KHÔNG ĐƯỢC
                                            TƯƠNG TÁC VỚI HỆ THỐNG</span>
                                    </blockquote>
                                </div>
                            </div>

                            <div class="card notification text-bg-success mb-3" id="notification-take-note"
                                style="display: none; max-width: 100%;">
                                <div class="card-header text-dark">
                                    <span>CHÚ Ý:</span>
                                </div>
                                <div class="card-body">
                                    <blockquote class="blockquote mb-0">
                                        <span>THỜI GIAN ĐỌC CÂU HỎI VÀ CHUẨN BỊ Ý TƯỞNG ĐANG ĐẾM NGƯỢC</span>
                                    </blockquote>
                                </div>
                            </div>

                            <div id="audioMotion" style="width: 100%; height: 250px; display: none"></div>
                        </div>
                        <div class="col-md-12 overflow-auto border-style form-area" style="height: 100%;" id="form-area">
                            @foreach ($testParts as $testPart)
                                <form
                                    @if ($testPart->testSkill->skill_name == 'Listening') action="/saveListening"
                                    @elseif ($testPart->testSkill->skill_name == 'Speaking') action="/saveSpeaking"
                                    @elseif ($testPart->testSkill->skill_name == 'Reading') action="/saveReading"
                                    @elseif ($testPart->testSkill->skill_name == 'Writing') action="/saveWriting" @endif
                                    method="post" id="testForm-{{ $testPart->testSkill->id }}" class="testForm"
                                    {{ $testPart->testSkill->skill_name == 'Speaking' ? 'enctype="multipart/form-data"' : '' }}>
                                    @csrf
                                    <input type="hidden" name="skill_id" id="skillId"
                                        value="{{ $testPart->testSkill->id }}">
                                    <input type="hidden" name="test_id" id="testId" value="{{ $test->id }}">
                                    <!-- Hiển thị Questions và Options -->
                                    @foreach ($testPart->testSkill->questions as $questionIndex => $question)
                                        <div class="skill-content m-2 question"
                                            id="part-{{ $testPart->testSkill->part_name }}"
                                            data-skill-id="{{ $testPart->testSkill->id }}"
                                            data-part-id="{{ $testPart->testSkill->part_name }}" style="display: none;">
                                            @if ($testPart->testSkill->skill_name !== 'Writing' && $testPart->testSkill->skill_name !== 'Speaking')
                                                <strong>
                                                    <p>Question {{ $question->question_number }}:
                                                        {!! nl2br($question->question_text) !!}</p>
                                                </strong>
                                            @endif
                                            @if ($testPart->testSkill->skill_name == 'Speaking')
                                                <strong>
                                                    <p>Topic {{ $question->question_number }}:
                                                        {!! nl2br($question->question_text) !!}</p>
                                                </strong>
                                            @endif
                                            @if ($testPart->testSkill->skill_name == 'Writing')
                                                <div class="showCount d-flex justify-content-end">
                                                    <strong>
                                                        <div id="wordCount_{{ $question->id }}" class="countWord">0 words
                                                        </div>
                                                    </strong>
                                                </div>
                                                <textarea name="responses[{{ $question->id }}]" id="response_{{ $question->id }}" class="form-control no-select"
                                                    rows="17" placeholder="Type your response here..."></textarea>
                                            @endif
                                            <div class="options-container">
                                                @if ($testPart->testSkill->skill_name !== 'Speaking')
                                                    @foreach ($question->options as $index => $option)
                                                        <div class="option">
                                                            <label>
                                                                <input type="radio" class="radio-scaled"
                                                                    name="responses[{{ $question->id }}]"
                                                                    value="{{ $option->id }}">
                                                                {{ chr(65 + $index) }}. {!! nl2br($option->option_text) !!}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    @foreach ($question->options as $index => $option)
                                                        <div class="option">
                                                            {{ $index + 1 }}. {!! nl2br($option->option_text) !!}
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            @if (
                                                $testPart->testSkill->part_name == 'Part_1' &&
                                                    $questionIndex == 1 &&
                                                    $testPart->testSkill->skill_name == 'Speaking')
                                                <div class="recording-controls" data-question-id="{{ $question->id }}"
                                                    data-part-id="{{ $testPart->testSkill->part_name }}"
                                                    data-skill-id="{{ $testPart->testSkill->id }}">
                                                    <button type="button" class="startRecording" hidden>Bắt đầu ghi âm</button>
                                                    <button type="button" class="stopRecording" disabled hidden>Dừng ghi âm</button>
                                                    <audio class="audioPlayback" controls hidden></audio>
                                                </div>
                                            @endif
                                            @if ($testPart->testSkill->part_name == 'Part_2' && $testPart->testSkill->skill_name == 'Speaking')
                                                <div class="recording-controls" data-question-id="{{ $question->id }}"
                                                    data-part-id="{{ $testPart->testSkill->part_name }}"
                                                    data-skill-id="{{ $testPart->testSkill->id }}">
                                                    <button type="button" class="startRecording" hidden>Bắt đầu ghi
                                                        âm</button>
                                                    <button type="button" class="stopRecording" disabled hidden>Dừng ghi
                                                        âm</button>
                                                    <audio class="audioPlayback" controls hidden></audio>
                                                </div>
                                            @endif
                                            @if ($testPart->testSkill->part_name == 'Part_3' && $testPart->testSkill->skill_name == 'Speaking')
                                                <div class="recording-controls" data-question-id="{{ $question->id }}"
                                                    data-part-id="{{ $testPart->testSkill->part_name }}"
                                                    data-skill-id="{{ $testPart->testSkill->id }}">
                                                    <button type="button" class="startRecording" hidden>Bắt đầu ghi
                                                        âm</button>
                                                    <button type="button" class="stopRecording" disabled hidden>Dừng ghi
                                                        âm</button>
                                                    <audio class="audioPlayback" controls hidden></audio>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="responses[]" value="">
                                </form>
                            @endforeach
                        </div>
                        {{-- End col --}}
                    </div>
                </div>
                {{-- End body --}}
            </div>
        </div>
    </div>
    </div>
    {{-- Model for PopUp --}}
    <!-- Bootstrap Modal for Speaking Preparation -->
    <div class="modal fade" id="speakingPrepModal" tabindex="-1" role="dialog"
        aria-labelledby="speakingPrepModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="speakingPrepModalLabel">Chuẩn bị cho phần Speaking</h5>
                </div>
                <div class="modal-body">
                    <div class="bound d-flex justify-content-center">
                        <div class="image-avatar d-flex justify-content-center">
                            <img src="{{ asset('students/assets/images/boy.png') }}" alt="Boy with headphone">
                        </div>
                    </div>
                    <div class="caution">
                        <h3>BẠN ĐEO TAI NGHE ĐỂ LÀM BÀI THI NÓI</h3>
                    </div>
                    <div class="caution">
                        <h5>Bạn có 60 giây để chuẩn bị</h5>
                    </div>
                    <h4 id="prepTimer">60</h4>
                    <div class="note">
                        <h5>BẠN SẼ ĐƯỢC THU ÂM TRỰC TIẾP</h5>
                    </div>
                    <div class="note">
                        <h5>TRONG LÚC THU ÂM KHÔNG TƯƠNG TÁC VỚI HỆ THỐNG</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Start -->
    <footer class="footer fixed-footer">
        @php
            $skillsGrouped = $testParts->groupBy(function ($testPart) {
                return $testPart->testSkill->skill_name;
            });
        @endphp

        @foreach ($skillsGrouped as $skillName => $groupedParts)
            <div class="skill-section">
                <div class="btn-group">
                    @php
                        $usedParts = [];
                        $buttonIndex = 0;
                    @endphp
                    @foreach ($groupedParts as $testPart)
                        @foreach ($testPart->testSkill->questions as $part)
                            @if (!in_array($part->part_name, $usedParts))
                                <button class="btn btn-secondary btn-sm skill-part-btn"
                                    data-skill-id="{{ $testPart->testSkill->id }}" data-part-id="{{ $part->part_name }}"
                                    data-skill-name= "{{ $skillName }}"
                                    data-time-limit="{{ $testPart->testSkill->time_limit }}">
                                    {{ str_replace('_', ' ', $part->part_name) }}
                                </button>
                                @php
                                    $usedParts[] = $part->part_name;
                                    $buttonIndex++;
                                @endphp
                            @endif
                        @endforeach
                    @endforeach
                </div>
                <div class="skill-timer badge {{ $badgeColors[$skillName] ?? 'bg-primary' }}">
                    {{ $skillName }} -
                    {{ $groupedParts->first()->testSkill->time_limit == '01:00:00' ? '60' : explode(':', $groupedParts->first()->testSkill->time_limit)[1] }}
                </div>
            </div>
        @endforeach
        <!-- Controls Column -->
        <div class="skill-section">
            <div class="btn-group">
                <button class="btn btn-warning mb-2" id="next-skill-btn">Tiếp tục</button>
                <button class="btn btn-primary mb-2" id="save-btn">Lưu bài</button>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.webrtc-experiment.com/RecordRTC.js"></script>
    <script type="module" src="{{ asset('students/assets/js/recording.js') }}"></script>
    <script src="{{ asset('students/assets/js/display_test_page.js') }}"></script>
    <script src="{{ asset('students/assets/js/record_speaking.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const audio = document.getElementById('audioPlayer-{{ $audio->id }}');
            let lastTime = 0; // Biến lưu trữ thời gian phát cuối cùng

            audio.addEventListener('timeupdate', function () {
                // Nếu thời gian hiện tại lớn hơn thời gian cuối cùng + 1s, tức là người dùng đang cố gắng tua
                if (audio.currentTime > lastTime + 1) {
                    audio.currentTime = lastTime; // Thiết lập lại thời gian phát về thời gian cuối cùng
                } else {
                    lastTime = audio.currentTime; // Cập nhật thời gian phát cuối cùng
                }
            });
            audio.addEventListener('seeking', function () {
                if (audio.currentTime !== lastTime) {
                    audio.currentTime = lastTime; // Thiết lập lại thời gian phát về thời gian cuối cùng
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to handle saving the answer to the database
            function saveAnswer(skillId, questionId, optionId, testId) {
                $.ajax({
                    url: '/saveAnswer', // URL to save the answer
                    type: 'POST',
                    data: {
                        skill_id: skillId,
                        question_id: questionId,
                        option_id: optionId,
                        test_id: testId,
                        _token: $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        // console.log('Answer saved successfully.');
                    },
                    error: function(error) {
                        console.error('Error saving answer:', error);
                    }
                });
            }

            // Event listener for radio button change
            $('input[type="radio"]').on('change', function() {
                var skillId = $(this).closest('.skill-content').data('skill-id');
                var questionId = $(this).attr('name').match(/\d+/)[0];
                var optionId = $(this).val();
                var testId = $(this).closest('form').find('input[name="test_id"]').val();
                saveAnswer(skillId, questionId, optionId, testId);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#submitTestButton").click(function() {
                var testId = $(this).data("test-id");
                var testResultUrl = `/students/tests/${testId}/results`;
                endSession(testId);
                Swal.fire({
                    html: `
                        <h3>Bạn đã hoàn thành bài kiểm tra</h3>
                        <h4>Hệ thống sẽ nộp bài tự động</h4>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, nộp bài!',
                    cancelButtonText: 'Không'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#save-btn").click();
                        localStorage.clear();
                        location.reload();
                        setTimeout(function() {
                            window.location.replace(testResultUrl);
                        }, 500); // Chờ 500 ms
                    }
                });
            });

            function endSession(testId) {
                const url = `/students/tests/${testId}/session/end`;
                fetch(url, {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => console.log("Session ended:", data))
                    .catch(error => console.error("Error ending session:", error));
            }
        });
    </script>
@endsection
