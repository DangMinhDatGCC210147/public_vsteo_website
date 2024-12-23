<?php

namespace App\Exports;

use App\Models\TestResult;
use App\Models\ExamRooms;
use App\Models\StudentExamRoom;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoomTestResultsExport implements FromCollection, WithHeadings
{
    protected $roomId;

    public function __construct($roomId)
    {
        $this->roomId = $roomId;
    }

    public function collection()
    {
        $room = ExamRooms::findOrFail($this->roomId);
        $startDate = $room->start_time;
        $endDate = $room->end_time;

        // Lấy danh sách ID của sinh viên từ bảng StudentExamRoom
        $studentIds = StudentExamRoom::where('room_id', $this->roomId)->pluck('user_id')->toArray();

        // Lấy tất cả sinh viên trong phòng thi
        $students = User::whereIn('id', $studentIds)->get();

        // Lấy kết quả thi của các sinh viên
        $testResults = TestResult::with('student')
            ->whereIn('student_id', $studentIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Tạo một bản đồ từ student_id sang testResult để truy cập nhanh
        $resultsMap = $testResults->keyBy('student_id');

        return $students->map(function ($student, $index) use ($resultsMap) {
            $testResult = $resultsMap[$student->id] ?? null;

            $writingScore = $testResult ? (round((($testResult->writing_part1 + $testResult->writing_part2 * 2) / 3) * 2) / 2) : null;
            $speakingScore = $testResult ? (round((($testResult->speaking_part1 + $testResult->speaking_part2 + $testResult->speaking_part3) / 3) * 2) / 2) : null;
            $listeningScore = $testResult ? $this->calculateScoreListening($testResult->listening_correctness) : null;
            $readingScore = $testResult ? $this->calculateScoreReading($testResult->reading_correctness) : null;

            // Chỉ tính điểm tổng nếu tất cả 4 kỹ năng đều có điểm
            $overallScore = null;
            if ($testResult->writing_part1 !== null
                && $testResult->writing_part2 !== null
                && $testResult->speaking_part1 !== null
                && $testResult->speaking_part2 !== null
                && $testResult->speaking_part3 !== null
                && $listeningScore !== null && $readingScore !== null) {
                $overallScore = round((
                    $listeningScore +
                    $readingScore +
                    $writingScore +
                    $speakingScore
                ) / 4 * 2) / 2;
            }

            return [
                'No' => $index + 1,
                'Student Name' => $student->name,
                'Student ID' => $student->account_id,
                'Test Name' => $testResult->test_name ?? '-',
                'Listening' => $listeningScore ?? '-',
                'Reading' => $readingScore ?? '-',
                'Writing' => $writingScore ?? '-',
                'Speaking' => $speakingScore ?? '-',
                'Overall' => $overallScore !== null ? $overallScore : '-',
                'Date Finish' => $testResult ? $testResult->created_at->toDateString() : '-',
            ];
        });
    }


    public function headings(): array
    {
        return [
            'No',
            'Student Name',
            'Student ID',
            'Test Name',
            'Listening',
            'Reading',
            'Writing',
            'Speaking',
            'Overall',
            'Date Finish'
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
