<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class TestSkill extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = ['test_id', 'skill_name', 'part_name', 'time_limit'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['skill_name']
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function testParts()
    {
        return $this->hasMany(TestPart::class, 'test_skill_id');
    }
    
    public function readingsAudios()
    {
        return $this->hasMany(ReadingsAudio::class);
    }    
}
