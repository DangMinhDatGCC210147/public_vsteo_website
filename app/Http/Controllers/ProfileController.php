<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile($slug)
    {
        // dd($slug);
        $student = User::where('slug', $slug)->first();

        $testResults = TestResult::with('student')
            ->where('student_id', $student->id)
            ->get();

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
        // dd($student);
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }
        return view('students.profile', compact('student', 'testResults'));
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

}
