<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchingHeadline extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 'headline', 'match_text'
    ];

    public function question()
    {
        return $this->belongsTo(QuestionHomework::class, 'question_id');
    }
}
