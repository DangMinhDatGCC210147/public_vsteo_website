<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\Student;
use App\Models\StudentResponses;
use App\Models\Test;
use App\Models\TestPart;
use App\Models\TestResult;
use App\Models\TestSkill;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Redirect;

class StudentController extends Controller
{
    public function index()
    {
        $user_name = Session::get('user_name');
        $user_email = Session::get('user_email');
        $parts = explode('@', $user_email);
        $user_id_student = $parts[0];
        $account_id = Session::get('account_id');
        $slug = Session::get('slug');
        // Truyền dữ liệu đến view
        return view('students.index', [
            'user_name' => $user_name,
            'user_email' => $user_email,
            'user_id_student' => $user_id_student,
            'account_id' => $account_id,
            'slug' => $slug,
        ]);
    }

    public function store(Request $request)
    {
        // // Lưu thông tin hình ảnh mới
        // $imagePath = $request->file('image')->store('imageStudents', 'public');

        // // Tìm kiếm student với user_id
        // $student = Student::where('user_id', $request->accountId)->first();

        // if ($student) {
        //     // Nếu đã có hình, xóa hình cũ
        //     if ($student->image_file && Storage::disk('public')->exists($student->image_file)) {
        //         Storage::disk('public')->delete($student->image_file);
        //     }
        //     // Cập nhật hình mới
        //     $student->update(['image_file' => $imagePath]);
        //     $message = 'Image updated successfully';
        // } else {
        //     // Tạo mới student với hình ảnh
        //     $student = Student::create([
        //         'user_id' => $request->accountId,
        //         'image_file' => $imagePath,
        //     ]);
        //     $message = 'Image created successfully';
        // }

        // return response()->json(['message' => $message, 'student' => $student], 200);
        if (!$request->hasFile('image')) {
            return response()->json(['message' => 'No image file found in the request'], 400);
        }

        // Kiểm tra tệp tin có hợp lệ không
        if (!$request->file('image')->isValid()) {
            return response()->json(['message' => 'Uploaded file is not valid'], 400);
        }

        // Lưu thông tin hình ảnh mới
        $imagePath = $request->file('image')->store('imageStudents', 'public');

        // Tạo mới student với hình ảnh
        $student = Student::create([
            'user_id' => $request->accountId,
            'image_file' => $imagePath,
        ]);

        $message = 'Image created successfully';

        return response()->json(['message' => $message, 'student' => $student], 200);
    }

    public function startTest()
    {
        $userId = Auth::user()->id; // Lấy user_id của người dùng hiện tại
        //BỔ SUNG TẠI ĐÂY
        // $student = Student::create([
        //     'user_id' => $userId,
        // ]);

        $student = Student::where('user_id', $userId)->orderBy('created_at', 'desc')->first(); // Lấy thông tin sinh viên từ DB

        if (!$student) {
            return redirect()->route('student.index')->with('error', 'Bạn cần chụp ảnh trước khi nhận đề thi.');
        }

        if ($student) {
            // Tạo bài test mới nếu người dùng chưa có test_id
            $randomNumbers = '';
            for ($i = 0; $i < 10; $i++) {
                $randomNumbers .= random_int(0, 9);
            }
            $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $randomLetters = '';
            for ($i = 0; $i < 3; $i++) {
                $randomIndex = random_int(0, strlen($letters) - 1);
                $randomLetters .= $letters[$randomIndex];
            }

            $testName = 'Test_' . $randomNumbers . $randomLetters;

            $test = Test::create([
                'duration' => '03:00:00',
                'test_name' => $testName,
            ]);
            $testId = $test->id;
            $student->test_id = $testId;
            $student->save();

            // Phân bổ ngẫu nhiên các phần thi cho sinh viên
            $skills = [
                'Listening' => ['Part_1', 'Part_2', 'Part_3'],
                'Reading' => ['Part_1', 'Part_2', 'Part_3', 'Part_4'],
                'Writing' => ['Part_1', 'Part_2'],
                'Speaking' => ['Part_1', 'Part_2', 'Part_3'],
            ];

            foreach ($skills as $skill => $parts) {
                foreach ($parts as $partName) {
                    $selectedPart = TestSkill::where('skill_name', $skill)
                        ->where('part_name', $partName)
                        ->inRandomOrder()
                        ->limit(1)
                        ->first();

                    if ($selectedPart) {
                        $testPart = TestPart::create([
                            'student_id' => $student->id,
                            'test_skill_id' => $selectedPart->id,
                            'test_id' => $testId,
                        ]);
                    }
                }
            }

            // Chuyển hướng đến trang làm bài thi mới tạo nếu có slug
            if ($test && $test->slug) {
                return redirect()->route('examination-page', ['slug' => $test->slug]);
            } else {
                return Redirect::back()->with('error', 'Không tạo được bài test mới.');
            }
        }
    }

    public function displayTest($slug)
    {
        if (empty($slug)) {
            return redirect()->back()->withErrors('No test parts found.');
        }

        $test = Test::with([
            'testParts.testSkill.questions.options',
            'testParts.testSkill.readingsAudios'
        ])->where('slug', $slug)->firstOrFail();
        $testParts = $test->testParts;
        // dd($testParts);
        return view('students.displayTest', compact('testParts', 'test'));
    }

    public function showTestResult($testId)
    {
        // Lấy skill IDs cho Reading và Listening
        $skills = TestSkill::whereIn('skill_name', ['Reading', 'Listening'])->get();
        $readingSkillIds = $skills->where('skill_name', 'Reading')->pluck('id');
        $listeningSkillIds = $skills->where('skill_name', 'Listening')->pluck('id');

        $student = auth()->user();
        $studentId = $student->id; // Lưu ID của user trước khi logout
        $studentName = $student->name;
        $studentEmail = $student->email;
        $accountId = $student->account_id;
        // Lấy tên của bài kiểm tra
        // dd($testId);
        $testName = Test::find($testId)->test_name;

        // Lấy tất cả phản hồi của học sinh có skill_id là Reading hoặc Listening
        $studentResponses = StudentResponses::where('student_id', $studentId)
            ->where('test_id', $testId)
            ->whereIn('skill_id', $readingSkillIds->merge($listeningSkillIds))
            ->get();
        $correctAnswersReading = 0;
        $correctAnswersListening = 0;

        // Duyệt qua từng câu trả lời của học sinh và xác định nếu nó đúng
        foreach ($studentResponses as $response) {
            $option = Option::where('question_id', $response->question_id)
                ->where('id', $response->text_response)
                ->first();

            if ($option && $option->correct_answer) {
                if ($readingSkillIds->contains($response->skill_id)) {
                    $correctAnswersReading++;
                } elseif ($listeningSkillIds->contains($response->skill_id)) {
                    $correctAnswersListening++;
                }
            }
        }

        function calculateScoreReading($correctAnswers)
        {
            if ($correctAnswers == 0) return 0;
            if ($correctAnswers == 1) return 0.5;
            if ($correctAnswers == 2) return 1.0;
            if ($correctAnswers >= 3 && $correctAnswers <= 4) return 1.5;
            if ($correctAnswers >= 5 && $correctAnswers <= 6) return 2.0;
            if ($correctAnswers >= 7 && $correctAnswers <= 8) return 2.5;
            if ($correctAnswers == 9) return 3.0;
            if ($correctAnswers == 10) return 3.5;
            if ($correctAnswers >= 11 && $correctAnswers <= 12) return 4.0;
            if ($correctAnswers >= 13 && $correctAnswers <= 14) return 4.5;
            if ($correctAnswers >= 15 && $correctAnswers <= 16) return 5.0;
            if ($correctAnswers >= 17 && $correctAnswers <= 18) return 5.5;
            if ($correctAnswers >= 19 && $correctAnswers <= 21) return 6.0;
            if ($correctAnswers >= 22 && $correctAnswers <= 24) return 6.5;
            if ($correctAnswers >= 25 && $correctAnswers <= 27) return 7.0;
            if ($correctAnswers >= 28 && $correctAnswers <= 30) return 7.5;
            if ($correctAnswers >= 31 && $correctAnswers <= 32) return 8.0;
            if ($correctAnswers >= 33 && $correctAnswers <= 34) return 8.5;
            if ($correctAnswers >= 35 && $correctAnswers <= 36) return 9.0;
            if ($correctAnswers >= 37 && $correctAnswers <= 38) return 9.5;
            if ($correctAnswers >= 39 && $correctAnswers <= 40) return 10.0;
            return 0;
        }

        function calculateScoreListening($correctAnswers)
        {
            if ($correctAnswers >= 0 && $correctAnswers <= 1) return 0;
            if ($correctAnswers == 2) return 0.5;
            if ($correctAnswers == 3) return 1.0;
            if ($correctAnswers == 4) return 1.5;
            if ($correctAnswers >= 5 && $correctAnswers <= 6) return 2.0;
            if ($correctAnswers == 7) return 2.5;
            if ($correctAnswers >= 8 && $correctAnswers <= 9) return 3.0;
            if ($correctAnswers >= 10 && $correctAnswers <= 11) return 3.5;
            if ($correctAnswers >= 12 && $correctAnswers <= 13) return 4.0;
            if ($correctAnswers >= 14 && $correctAnswers <= 15) return 4.5;
            if ($correctAnswers >= 16 && $correctAnswers <= 17) return 5.0;
            if ($correctAnswers >= 18 && $correctAnswers <= 19) return 5.5;
            if ($correctAnswers >= 20 && $correctAnswers <= 21) return 6.0;
            if ($correctAnswers == 22) return 6.5;
            if ($correctAnswers >= 23 && $correctAnswers <= 24) return 7.0;
            if ($correctAnswers == 25) return 7.5;
            if ($correctAnswers >= 26 && $correctAnswers <= 27) return 8.0;
            if ($correctAnswers >= 28 && $correctAnswers <= 29) return 8.5;
            if ($correctAnswers >= 30 && $correctAnswers <= 31) return 9.0;
            if ($correctAnswers >= 32 && $correctAnswers <= 33) return 9.5;
            if ($correctAnswers >= 34 && $correctAnswers <= 35) return 10.0;
            return 0;
        }

        $scoreReading = calculateScoreReading($correctAnswersReading);
        $scoreListening = calculateScoreListening($correctAnswersListening);

        // Lưu kết quả vào bảng test_results
        TestResult::updateOrCreate(
            [
                'student_id' => $studentId,
                'test_name' => $testName
            ],
            [
                'listening_correctness' => $correctAnswersListening,
                'reading_correctness' => $correctAnswersReading
            ]
        );

        // Truyền dữ liệu vào view
        return view('students.resultStudent', [
            'correctAnswersReading' => $correctAnswersReading,
            'correctAnswersListening' => $correctAnswersListening,
            'scoreListening' => $scoreListening,
            'scoreReading' => $scoreReading,
            'testId' => $testId,
            'studentId' => $studentId,
            'studentName' => $studentName,
            'studentEmail' => $studentEmail,
            'accountId' => $accountId,
        ]);
    }
}
