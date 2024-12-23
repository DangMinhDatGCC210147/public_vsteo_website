<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['test_skill_id', 'reading_audio_id', 'question_number', 'part_name', 'question_text', 'question_type'];

    public function testSkill()
    {
        return $this->belongsTo(TestSkill::class);
    }

    public function readingsAudio()
    {
        return $this->belongsTo(ReadingsAudio::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function responses()
    {
        return $this->hasMany(StudentResponses::class, 'question_id');
    }
}
