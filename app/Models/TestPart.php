<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPart extends Model
{
    use HasFactory;
    protected $table = 'test_parts';
    protected $fillable = [
        'student_id',
        'test_skill_id',
        'test_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function testSkill()
    {
        return $this->belongsTo(TestSkill::class);
    }
    
    public function questions()
    {
        return $this->hasMany(Question::class, 'test_skill_id');
    }
}
