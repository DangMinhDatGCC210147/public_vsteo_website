<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'test_id', 'image_file'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
