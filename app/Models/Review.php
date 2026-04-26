<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'student_id', 'booking_id',
        'rating', 'comment', 'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'rating'       => 'integer',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function getStarsHtmlAttribute(): string
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $html .= $i <= $this->rating
                ? '<span class="text-yellow-400">&#9733;</span>'
                : '<span class="text-gray-600">&#9733;</span>';
        }
        return $html;
    }
}
