<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingsAudio extends Model
{
    use HasFactory;

    protected $table = 'readings_audios';
    protected $fillable = ['test_skill_id', 'reading_audio_file', 'part_name'];

    public function testSkill()
    {
        return $this->belongsTo(TestSkill::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }
    public function isAudio()
    {
        return preg_match('/\.(mp3|wav|aac)$/i', $this->reading_audio_file);
    }

    /**
     * Kiểm tra xem đối tượng có phải là hình ảnh không.
     */
    public function isImage()
    {
        return preg_match('/\.(jpeg|jpg|png|gif|bmp)$/i', $this->reading_audio_file);
    }

    /**
     * Kiểm tra xem đối tượng có phải là văn bản không.
     */
    public function isText()
    {
        // Nếu không phải file âm thanh và không phải hình ảnh, giả sử đó là văn bản
        return !$this->isAudio() && !$this->isImage();
    }

    public function responses()
    {
        return $this->hasMany(StudentResponses::class, 'test_skill_id', 'skill_id');
    }
}
