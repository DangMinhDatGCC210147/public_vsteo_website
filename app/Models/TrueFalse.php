<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrueFalse extends Model
{
    use HasFactory;

    protected $table = 'true_false';

    protected $fillable = [
        'question_id', 'correct_answer'
    ];

    protected $casts = [
        'correct_answer' => 'string'
    ];

    public function question()
    {
        return $this->belongsTo(QuestionHomework::class, 'question_id');
    }
}
