<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Test extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = ['test_code', 'test_name', 'duration', 'instructor_id', 'test_status', 'start_date', 'end_date'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['test_name', 'instructor_id']
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function instructor()
    {
        return $this->belongsTo(User::class);
    }

    public function testSkills()
    {
        return $this->hasMany(TestPart::class);
    }

    public function testParts()
    {
        return $this->hasMany(TestPart::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'test_id', 'id');
    }
}
