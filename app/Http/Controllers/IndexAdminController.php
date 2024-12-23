<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\ReadingsAudio;
use App\Models\TestResult;
use App\Models\TestSkill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IndexAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = User::where('role', 2)
            ->with(['sessions' => function ($query) {
                $query->whereNotNull('session_end')
                    ->whereRaw('session_end >= session_start');
            }, 'testResults']) // Bổ sung mối quan hệ để tính toán số lượng bài kiểm tra
            ->withCount('testResults as tests_count') // Thêm số lượng bài kiểm tra
            ->get()
            ->map(function ($user) {
                // Tính toán tổng thời gian làm việc
                $totalMinutes = $user->sessions->sum('duration');
                $hours = intdiv($totalMinutes, 60);
                $minutes = $totalMinutes % 60;
                $seconds = ($totalMinutes * 60) % 60;

                $user->total_duration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                return $user;
            })
            ->sortByDesc('total_duration');

        $person = User::where('role', 2)
            ->withCount('testResults')
            ->orderByDesc('test_results_count')
            ->first();

        $highestListening = TestResult::join('users', 'test_results.student_id', '=', 'users.id')
            ->select('users.name', 'test_results.listening_correctness')
            ->orderByDesc('listening_correctness')
            ->first();
        $highestReading = TestResult::join('users', 'test_results.student_id', '=', 'users.id')
            ->select('users.name', 'test_results.reading_correctness')
            ->orderByDesc('reading_correctness')
            ->first();

        $count  = User::where('role', 2) // Giả sử role = 2 là sinh viên
            ->whereHas('testResults') // Kiểm tra người dùng đã làm ít nhất một bài kiểm tra
            ->count();
        $totalStudentsCount = User::where('role', 2)->count();

        $testsPerDay = TestResult::selectRaw('DAYOFWEEK(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->get()
            ->mapWithKeys(function ($item) {
                // Chuyển đổi số ngày trong tuần thành tên ngày, giả sử MySQL và Sunday là 1
                $days = [1 => 'Sun', 2 => 'Mon', 3 => 'Tue', 4 => 'Wed', 5 => 'Thu', 6 => 'Fri', 7 => 'Sat'];
                return [$days[$item->day] => $item->count];
            });

        return view('admin.index', compact('students', 'person', 'highestListening', 'highestReading', 'count', 'totalStudentsCount', 'testsPerDay'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        // Khởi tạo các biến để lưu số lượng câu hỏi theo kỹ năng và phần
        $readingParts = [];
        $listeningParts = [];
        $writingParts = [];
        $speakingParts = [];

        // Đếm số câu theo từng part của kỹ năng Reading
        for ($i = 1; $i <= 4; $i++) {
            $part_name = 'Part_' . $i;
            $readingParts[$part_name] = TestSkill::where('skill_name', 'Reading')
                ->where('part_name', $part_name)
                ->count();
        }

        // Đếm số câu theo từng part của kỹ năng Listening
        for ($i = 1; $i <= 3; $i++) {
            $part_name = 'Part_' . $i;
            $listeningParts[$part_name] = TestSkill::where('skill_name', 'Listening')
                ->where('part_name', $part_name)
                ->count();
        }

        // Đếm số câu theo từng part của kỹ năng Writing
        for ($i = 1; $i <= 2; $i++) {
            $part_name = 'Part_' . $i;
            $writingParts[$part_name] = TestSkill::where('skill_name', 'Writing')
                ->where('part_name', $part_name)
                ->count();
        }

        // Đếm số câu theo từng part của kỹ năng Speaking
        for ($i = 1; $i <= 3; $i++) {
            $part_name = 'Part_' . $i;
            $speakingParts[$part_name] = TestSkill::where('skill_name', 'Speaking')
                ->where('part_name', $part_name)
                ->count();
        }

        // Truyền các biến này sang view
        return view('admin.questionBank', compact('readingParts', 'listeningParts', 'writingParts', 'speakingParts'));
    }

    public function showTableOfWritingQuestionBank()
    {
        // Truy vấn tất cả các kỹ năng có tên 'Writing'
        $writingQuestionBank = TestSkill::where('skill_name', 'Writing')->get();

        $questions = [];
        $passages = [];

        foreach ($writingQuestionBank as $index => $writingQuestion) {
            // Truy vấn câu hỏi đầu tiên từ bảng questions với test_skill_id tương ứng
            $question = Question::where('test_skill_id', $writingQuestion->id)->first();
            if ($question) {
                $questions[$index] = str_replace('&nbsp;', ' ', $question->part_name);

                // Truy vấn passage từ bảng readings_audios với test_skill_id tương ứng
                $readingAudio = ReadingsAudio::where('test_skill_id', $writingQuestion->id)->first();
                $passages[$index] = $readingAudio ? str_replace('&nbsp;', ' ', $readingAudio->reading_audio_file) : 'No passage available';
            } else {
                $questions[$index] = 'No question available';
                $passages[$index] = 'No passage available';
            }
        }

        return view('admin.listQuestionBank.listOfWriting', compact('writingQuestionBank', 'questions', 'passages'));
    }

    public function showTableOfListeningQuestionBank()
    {
        $listeningQuestionBank = TestSkill::where('skill_name', 'Listening')
            ->get();
        $questions = [];

        foreach ($listeningQuestionBank as $index => $listeningQuestion) {
            $question = Question::where('test_skill_id', $listeningQuestion->id)->first();
            $questions[$index] = $question ? $question->part_name : 'No question available';
        }

        return view('admin.listQuestionBank.listOfListening', compact('listeningQuestionBank', 'questions'));
    }
    public function showTableOfReadingQuestionBank()
    {
        // Truy vấn tất cả các kỹ năng có tên 'Reading'
        $readingQuestionBank = TestSkill::where('skill_name', 'Reading')->get();

        $questions = [];
        $readingAudioFiles = [];

        foreach ($readingQuestionBank as $index => $readingQuestion) {
            // Truy vấn câu hỏi đầu tiên từ bảng questions với test_skill_id tương ứng
            $question = Question::where('test_skill_id', $readingQuestion->id)->first();
            if ($question) {
                $questions[$index] = $question->part_name;

                // Truy vấn reading_audio_file từ bảng readings_audios với test_skill_id tương ứng
                $readingAudio = ReadingsAudio::where('test_skill_id', $readingQuestion->id)->first();
                $readingAudioFiles[$index] = $readingAudio ? $readingAudio->reading_audio_file : 'No audio file available';
            } else {
                $questions[$index] = 'No question available';
                $readingAudioFiles[$index] = 'No audio file available';
            }
        }

        return view('admin.listQuestionBank.listOfReading', compact('readingQuestionBank', 'questions', 'readingAudioFiles'));
    }

    public function showTableOfSpeakingQuestionBank()
    {
        $speakingQuestionBank = TestSkill::where('skill_name', 'Speaking')
            ->get();
        $questions = [];

        foreach ($speakingQuestionBank as $index => $speakingQuestion) {
            $question = Question::where('test_skill_id', $speakingQuestion->id)->first();
            $questions[$index] = $question ? $question->part_name : 'No question available';
        }

        return view('admin.listQuestionBank.listOfSpeaking', compact('speakingQuestionBank', 'questions'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
