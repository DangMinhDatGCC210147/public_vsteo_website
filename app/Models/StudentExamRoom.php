<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExamRoom extends Model
{
    use HasFactory;

    protected $table = 'student_exam_room';

    protected $fillable = [
        'user_id',
        'room_id',
    ];
    
}
