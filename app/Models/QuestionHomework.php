<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionHomework extends Model
{
    use HasFactory;

    protected $table = 'question_homeworks';

    protected $fillable = [
        'assignment_id', 'question_text', 'question_type'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function multipleChoiceOptions()
    {
        return $this->hasMany(MultipleChoiceOption::class, 'question_id');
    }

    public function fillInTheBlanks()
    {
        return $this->hasMany(FillInTheBlank::class, 'question_id');
    }

    public function trueFalse()
    {
        return $this->hasOne(TrueFalse::class, 'question_id');
    }

    public function matchingHeadlines()
    {
        return $this->hasMany(MatchingHeadline::class, 'question_id');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'question_id');
    }
}
