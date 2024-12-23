<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserSessions extends Model
{
    use HasFactory;

    protected $table = 'user_sessions';

    protected $fillable = ['user_id', 'session_start', 'session_end'];

    protected $casts = [
        'session_start' => 'datetime',
        'session_end' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationAttribute()
    {
        if (!$this->session_start || !$this->session_end) {
            return 0;
        }
        $start = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->session_start);
        $end = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->session_end);

        if ($end->lessThan($start)) {
            return 0;
        }
        // Tính khoảng cách theo phút mà không xét đến ngày
        $duration = abs($end->diffInMinutes($start, false));

        return $duration;
    }
}
