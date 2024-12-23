<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'question_id',
        'answer_text',
        'is_correct',
        'attempt_number',
    ];
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function question()
    {
        return $this->belongsTo(QuestionHomework::class, 'question_id');
    }
}
