<?php

namespace App\Imports;

use App\Models\StudentExamRoom;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsRoomImport implements ToCollection, WithHeadingRow
{
    protected $roomId;
    public $errors = [];

    public function __construct($roomId)
    {
        $this->roomId = $roomId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Tìm người dùng dựa trên account_id
            $student = User::where('account_id', $row['accountid'])->first();

            if (!$student) {
                $this->errors[] = "Student with student ID {$row['accountid']} not found.";
                continue;
            }
            // Nếu người dùng tồn tại, tạo bản ghi trong bảng student_exam_room
            if ($student && $student->is_active) {
                // Kiểm tra xem bản ghi đã tồn tại trong student_exam_room chưa
                $exists = StudentExamRoom::where('user_id', $student->id)
                    ->where('room_id', $this->roomId)
                    ->exists();

                // Nếu chưa tồn tại, tạo bản ghi mới
                if (!$exists) {
                    StudentExamRoom::create([
                        'user_id' => $student->id,
                        'room_id' => $this->roomId,
                    ]);
                }
            }else {
                $this->errors[] = "Student with student ID {$row['accountid']} is not active.";
            }
        }
    }
}
