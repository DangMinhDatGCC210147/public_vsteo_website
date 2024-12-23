<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FillInTheBlank extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 'blank_position', 'correct_answer'
    ];

    public function question()
    {
        return $this->belongsTo(QuestionHomework::class, 'question_id');
    }
}
