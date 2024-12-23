<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentResponses;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\TestSkill;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ResultsExport;
use App\Exports\TestResultsExport;
use App\Models\Question;
use App\Models\ReadingsAudio;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Shared\Html;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use App\Models\ReadingsAudios;
use HTMLPurifier;
use HTMLPurifier_Config;
use ZipArchive;


class ShowListResultsController extends Controller
{
    public function index()
    {
        $testResults = TestResult::with('student')->get();

        $testResults->map(function ($testResult) {
            // Assuming `reading_correctness` and `listening_correctness` store the number of correct answers
            $testResult->computed_reading_score = $this->calculateScoreReading($testResult->reading_correctness);
            $testResult->computed_listening_score = $this->calculateScoreListening($testResult->listening_correctness);

            // Compute other scores if necessary, example:
            $rawScore = ($testResult->writing_part1 + $testResult->writing_part2 * 2) / 3;
            $testResult->computed_writing_score = round($rawScore * 2) / 2;

            $averageSpeaking = ($testResult->speaking_part1 + $testResult->speaking_part2 + $testResult->speaking_part3) / 3;
            $testResult->speaking = round($averageSpeaking * 2) / 2;

            // Calculate the average score combining reading, listening, writing and speaking
            $average = (
                $testResult->computed_listening_score +
                $testResult->computed_reading_score +
                $testResult->computed_writing_score +
                $testResult->speaking
            ) / 4;

            $testResult->average_score = round($average * 2) / 2;
            return $testResult;
        });

        return view('admin.showResult', compact('testResults'));
    }

    public function detail($id)
    {
        $testResult = TestResult::with(['student', 'test' => function ($query) {
            $query->select('id', 'test_name');
        }])->where('id', $id)->first();

        if (!$testResult) {
            abort(404, 'Test result not found.');
        }

        $testId = Test::where('test_name', $testResult->test_name)->value('id');
        $student = null;
        $speakingResponses = [];

        if ($testId) {
            $student = Student::where('user_id', $testResult->student_id)
                ->where('test_id', $testId)
                ->first();
        }
        if ($student) {
            $user = User::where('id', $student->user_id)->first();

            // Lấy skill_id từ bảng test_skill với skill_name là speaking
            $speakingSkillId = DB::table('test_skills')
                ->where('skill_name', 'Speaking')
                ->pluck('id');

            // Truy vấn để lấy danh sách text_response từ bảng student_responses
            $speakingResponses = DB::table('student_responses')
                ->where('student_id', $user->id)
                ->where('test_id', $testId)
                ->whereIn('skill_id', $speakingSkillId)
                ->pluck('text_response');
        } else {
            return redirect()->back()->with('error', 'No corresponding student found for the given test result.');
        }

        // Calculate scores only if a corresponding student is found
        $testResult->computed_reading_score = $this->calculateScoreReading($testResult->reading_correctness);
        $testResult->computed_listening_score = $this->calculateScoreListening($testResult->listening_correctness);
        $rawScore = ($testResult->writing_part1 + $testResult->writing_part2 * 2) / 3;
        $testResult->computed_writing_score = round($rawScore * 2) / 2;
        $averageSpeaking = ($testResult->speaking_part1 + $testResult->speaking_part2 + $testResult->speaking_part3) / 3;
        $testResult->speaking = round($averageSpeaking * 2) / 2;
        $average = (
            $testResult->computed_listening_score +
            $testResult->computed_reading_score +
            $testResult->computed_writing_score +
            $testResult->speaking
        ) / 4;
        $testResult->average_score = round($average * 2) / 2;
        return view('admin.resultDetail', compact('testResult', 'student', 'user', 'speakingResponses'));
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

    public function downloadResponse($studentId, $testName)
    {
        $testId = Test::where('test_name', $testName)->value('id');
        if (!$testId) {
            return redirect()->back()->with('error', 'Test is not available.');
        }

        $speakingSkillIds = TestSkill::where('skill_name', 'Speaking')->pluck('id');
        $writingSkillIds = TestSkill::where('skill_name', 'Writing')->pluck('id');

        if ($speakingSkillIds->isEmpty() || $writingSkillIds->isEmpty()) {
            return redirect()->back()->with('error', 'Skill IDs for Speaking or Writing not found.');
        }

        $accountId = User::where('id', $studentId)->value('account_id');
        $studentName = User::where('id', $studentId)->value('slug');
        $responses = StudentResponses::where('student_id', $studentId)
            ->where('test_id', $testId)
            ->whereIn('skill_id', $speakingSkillIds->merge($writingSkillIds)->toArray())
            ->get();

        if ($responses->isEmpty()) {
            return redirect()->back()->with('error', 'Responses not found.');
        }

        $responsesFolderPath = storage_path('app/public/' . $accountId . '_' . $studentName);
        if (!file_exists($responsesFolderPath)) {
            mkdir($responsesFolderPath, 0777, true);
            mkdir($responsesFolderPath . '/speaking', 0777, true);
            mkdir($responsesFolderPath . '/writing', 0777, true);
        }

        $writingTaskCounter = 1;
        foreach ($responses as $response) {
            if ($speakingSkillIds->contains($response->skill_id)) {
                // Đối với kỹ năng nói, kiểm tra file tồn tại và sao chép
                $filePath = str_replace('\\', '/', public_path('storage/' . $response->text_response));
                if (file_exists($filePath)) {
                    copy($filePath, $responsesFolderPath . '/speaking/' . basename($filePath));
                } else {
                    $this->deleteDirectory($responsesFolderPath);
                    return redirect()->back()->with('error', 'Student did not submit Speaking or Writing or their submissions are not available');
                }
            } elseif ($writingSkillIds->contains($response->skill_id)) {
                // Đối với kỹ năng viết, tạo file docx từ text
                $phpWord = new PhpWord();
                $section = $phpWord->addSection();

                // Thêm question_text
                $questionText = Question::where('id', $response->question_id)->value('question_text');
                $section->addText("Question: " . $questionText);

                // Thêm reading_audio_file
                $readingAudioText = ReadingsAudio::where('test_skill_id', $response->skill_id)->value('reading_audio_file');
                $config = HTMLPurifier_Config::createDefault();
                $purifier = new HTMLPurifier($config);
                $cleanHtml = $purifier->purify($readingAudioText);
                Html::addHtml($section, $cleanHtml);

                // Thêm response
                $section->addTextBreak(1); // Thêm một khoảng trống giữa question và response
                $section->addText("Response:");

                $textResponses = explode("\n", $response->text_response);
                foreach ($textResponses as $line) {
                    $section->addText($line);
                }

                $docxFilePath = $responsesFolderPath . '/writing/writing_response_Task_' . $writingTaskCounter . '.docx';
                $writer = IOFactory::createWriter($phpWord, 'Word2007');
                $writer->save($docxFilePath);

                $writingTaskCounter++;
            }
        }

        // Zip the responses folder
        $zipFilePath = storage_path('app/public/' . $accountId . '_' . $studentName . '.zip');
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($responsesFolderPath));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($responsesFolderPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            // Return the response with the ZIP file download
            $this->deleteDirectory($responsesFolderPath);
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            $this->deleteDirectory($responsesFolderPath);
            return redirect()->back()->with('error', 'Could not create zip file.');
        }
    }

    protected function deleteDirectory($dirPath)
    {
        if (is_dir($dirPath)) {
            $files = scandir($dirPath);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $fullPath = "$dirPath/$file";
                    if (is_dir($fullPath)) {
                        $this->deleteDirectory($fullPath);
                    } else {
                        unlink($fullPath);
                    }
                }
            }
            rmdir($dirPath);
        }
    }

    public function downloadAllFiles()
    {
        $speakingSkillIds = TestSkill::where('skill_name', 'Speaking')->pluck('id');
        $writingSkillIds = TestSkill::where('skill_name', 'Writing')->pluck('id');

        if ($speakingSkillIds->isEmpty() || $writingSkillIds->isEmpty()) {
            return redirect()->back()->with('error', 'Skill IDs for Speaking or Writing not found.');
        }

        $responses = StudentResponses::with('test')
            ->whereIn('skill_id', $speakingSkillIds->merge($writingSkillIds)->toArray())
            ->get();

        if ($responses->isEmpty()) {
            return redirect()->back()->with('error', 'Responses not found.');
        }

        $baseFolderPath = storage_path('app/public/responses');
        if (!file_exists($baseFolderPath)) {
            mkdir($baseFolderPath, 0777, true);
        }

        foreach ($responses as $response) {
            $student = User::find($response->student_id);
            if (!$student) continue;

            $testName = $response->test ? $response->test->test_name : 'default_test_name';
            $responsesFolderPath = $baseFolderPath . '/' . $student->account_id . '_' . $student->slug . '_' . $testName;
            if (!file_exists($responsesFolderPath)) {
                mkdir($responsesFolderPath, 0777, true);
                mkdir($responsesFolderPath . '/speaking', 0777, true);
                mkdir($responsesFolderPath . '/writing', 0777, true);
            }

            if ($speakingSkillIds->contains($response->skill_id)) {
                $filePath = str_replace('\\', '/', public_path('storage/' . $response->text_response));
                if (file_exists($filePath)) {
                    copy($filePath, $responsesFolderPath . '/speaking/' . basename($filePath));
                }
            } elseif ($writingSkillIds->contains($response->skill_id)) {
                $phpWord = new PhpWord();
                $section = $phpWord->addSection();

                // Thêm question_text
                $questionText = Question::where('id', $response->question_id)->value('question_text');
                $section->addText("Question: " . $questionText);

                // Thêm reading_audio_file
                $readingAudioText = ReadingsAudio::where('test_skill_id', $response->skill_id)->value('reading_audio_file');
                Html::addHtml($section, $readingAudioText);

                // Thêm response
                $section->addTextBreak(1);
                $section->addText("Response:");
                $textResponses = explode("\n", $response->text_response);
                foreach ($textResponses as $line) {
                    $section->addText($line);
                }

                $writingTaskCounter = 1;
                $docxFilePath = $responsesFolderPath . '/writing/writing_response_Task_' . $writingTaskCounter . '.docx';
                $writer = IOFactory::createWriter($phpWord, 'Word2007');
                $writer->save($docxFilePath);
                $writingTaskCounter++;
            }
        }

        $zipFilePath = storage_path('app/public/all_responses.zip');
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseFolderPath));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($baseFolderPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            $this->deleteDirectory($baseFolderPath);
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'Could not create zip file.');
        }
    }

    // function getUniqueDirectory($baseFolderPath, $directoryName)
    // {
    //     $directory = $baseFolderPath . '/' . $directoryName;
    //     $i = 1;

    //     while (is_dir($directory)) {
    //         $directory = $baseFolderPath . '/' . $directoryName . '_' . $i;
    //         $i++;
    //     }

    //     return $directory;
    // }

    public function markResponse($studentId, $testName, TestResult $resultId = null)
    {
        $student = User::find($studentId);
        return view('admin.markResponse', compact('student', 'studentId', 'testName', 'resultId'));
    }

    public function updateMark(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'writing_part1' => 'required|numeric',
            'writing_part2' => 'required|numeric',
            'speaking_part1' => 'required|numeric',
            'speaking_part2' => 'required|numeric',
            'speaking_part3' => 'required|numeric',

        ]);

        // Assuming you are passing a student_id or test_result_id with the form
        $testResult = TestResult::where('student_id', $request->studentId)
            ->where('test_name', $request->testName)
            ->first();
        // dd($testResult);
        if ($testResult) {
            $testResult->writing_part1 = $request->writing_part1;
            $testResult->writing_part2 = $request->writing_part2;
            $testResult->speaking_part1 = $request->speaking_part1;
            $testResult->speaking_part2 = $request->speaking_part2;
            $testResult->speaking_part3 = $request->speaking_part3;
            $testResult->save();

            return redirect()->route('resultList.index')->with('success', 'Scores updated successfully!');
        }

        return back()->with('error', 'Test Result not found.');
    }
    public function exportExcel()
    {
        return Excel::download(new TestResultsExport, 'test_results.xlsx');
    }

    public function downloadFilterDate(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $speakingSkillIds = TestSkill::where('skill_name', 'Speaking')->pluck('id');
        $writingSkillIds = TestSkill::where('skill_name', 'Writing')->pluck('id');

        if ($speakingSkillIds->isEmpty() || $writingSkillIds->isEmpty()) {
            return redirect()->back()->with('error', 'Skill IDs for Speaking or Writing not found.');
        }

        $responses = StudentResponses::with('test')
            ->whereIn('skill_id', $speakingSkillIds->merge($writingSkillIds)->toArray())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        if ($responses->isEmpty()) {
            return redirect()->back()->with('error', 'Responses not found.');
        }

        $baseFolderPath = storage_path('app/public/responses');
        if (!file_exists($baseFolderPath)) {
            mkdir($baseFolderPath, 0777, true); // Ensure the base directory exists
        }

        foreach ($responses as $response) {
            $student = User::find($response->student_id);
            if (!$student) continue;

            $testName = $response->test ? $response->test->test_name : 'default_test_name';
            $responsesFolderPath = $baseFolderPath . '/' . $student->account_id . '_' . $student->slug . '_' . $testName;
            if (!file_exists($responsesFolderPath)) {
                mkdir($responsesFolderPath, 0777, true); // Create directory if not exists
                mkdir($responsesFolderPath . '/speaking', 0777, true); // Speaking subdirectory
                mkdir($responsesFolderPath . '/writing', 0777, true); // Writing subdirectory
            }

            if ($speakingSkillIds->contains($response->skill_id)) {
                $filePath = str_replace('\\', '/', public_path('storage/' . $response->text_response));
                if (file_exists($filePath)) {
                    copy($filePath, $responsesFolderPath . '/speaking/' . basename($filePath));
                }
            } elseif ($writingSkillIds->contains($response->skill_id)) {
                $phpWord = new \PhpOffice\PhpWord\PhpWord();
                $section = $phpWord->addSection();

                // Thêm question_text
                $questionText = Question::where('id', $response->question_id)->value('question_text');
                $section->addText("Question: " . $questionText);

                // Thêm reading_audio_file
                $readingAudioText = ReadingsAudio::where('test_skill_id', $response->skill_id)->value('reading_audio_file');
                $config = HTMLPurifier_Config::createDefault();
                $purifier = new HTMLPurifier($config);
                $cleanHtml = $purifier->purify($readingAudioText);
                Html::addHtml($section, $cleanHtml);

                // Thêm response
                $section->addTextBreak(1);
                $section->addText("Response:");
                $textResponses = explode("\n", $response->text_response);
                foreach ($textResponses as $line) {
                    $section->addText($line);
                }

                $docxFilePath = $responsesFolderPath . '/writing/writing_response_Task_' . ($response->id % 2 == 0 ? 2 : 1) . '.docx';
                $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                $writer->save($docxFilePath);
            }
        }

        // Zip the base responses folder
        $zipFilePath = storage_path('app/public/responses_filtered.zip');
        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($baseFolderPath));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($baseFolderPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            $this->deleteDirectory($baseFolderPath);
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'Could not create zip file.');
        }
    }

    public function exportExcelFiltered(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new TestResultsExport($startDate, $endDate), 'test_results_filtered.xlsx');
    }
}
