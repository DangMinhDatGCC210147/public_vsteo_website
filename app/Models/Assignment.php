<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'isEnable',
        'teacher_id',
        'show_detailed_feedback',
        'duration'
    ];

    public function questions()
    {
        return $this->hasMany(QuestionHomework::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function studentAnswers()
    {
        return $this->hasManyThrough(StudentAnswer::class, QuestionHomework::class, 'assignment_id', 'question_id');
    }
}
