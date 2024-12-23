<?php

namespace App\Exports;

use App\Models\TestResult;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TestResultsExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = TestResult::with('student');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        $testResults = $query->get();

        return $testResults->map(function ($testResult, $index) {
            $writingScore = $testResult->writing_part1 !== null && $testResult->writing_part2 !== null ?
                round((($testResult->writing_part1 + $testResult->writing_part2 * 2) / 3) * 2) / 2 : null;
            $speakingScore = $testResult->speaking_part1 !== null && $testResult->speaking_part2 !== null && $testResult->speaking_part3 !== null ?
                round((($testResult->speaking_part1 + $testResult->speaking_part2 + $testResult->speaking_part3) / 3) * 2) / 2 : null;

            // Calculate Overall only if both writing and speaking scores are available
            $overallScore = null;
            if ($writingScore !== null && $speakingScore !== null) {
                $overallScore = round((
                    $this->calculateScoreListening($testResult->listening_correctness) +
                    $this->calculateScoreReading($testResult->reading_correctness) +
                    $writingScore +
                    $speakingScore
                ) / 4 * 2) / 2;
            }

            return [
                'No' => $index + 1,
                'Student Name' => $testResult->student->name ?? 'N/A',
                'Student ID' => $testResult->student->account_id,
                'Test Name' => $testResult->test_name,
                'Listening' => $this->calculateScoreListening($testResult->listening_correctness),
                'Reading' => $this->calculateScoreReading($testResult->reading_correctness),
                'Writing' => $writingScore,
                'Speaking' => $speakingScore,
                'Overall' => $overallScore,
                'Date Finish' => $testResult->created_at->toDateString(),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No', 'Student Name', 'Student ID', 'Test Name', 'Listening', 'Reading', 'Writing', 'Speaking', 'Overall', 'Date Finish'
        ];
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
