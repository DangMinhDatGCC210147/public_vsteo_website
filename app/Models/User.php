<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cviebrock\EloquentSluggable\Sluggable;

class User extends Authenticatable
{
    use HasFactory;
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'account_id',
        'role',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // /**
    //  * The accessors to append to the model's array form.
    //  *
    //  * @var array<int, string>
    //  */
    // protected $appends = [
    //     'profile_photo_url',
    // ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function testResults()
    {
        return $this->hasMany(TestResult::class, 'student_id');
    }
    public function sessions()
    {
        return $this->hasMany(UserSessions::class);
    }

    public function examRooms()
    {
        return $this->belongsToMany(ExamRooms::class, 'student_exam_room', 'user_id', 'room_id');
    }

}
