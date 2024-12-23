<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRooms extends Model
{
    use HasFactory;

    protected $table = 'exam_rooms';

    protected $fillable = [
        'room_name',
        'capacity',
        'start_time',
        'end_time',
    ];

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_exam_room', 'room_id', 'user_id');
    }
}
