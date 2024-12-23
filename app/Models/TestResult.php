<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'test_name',
        'listening_correctness',
        'reading_correctness',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
