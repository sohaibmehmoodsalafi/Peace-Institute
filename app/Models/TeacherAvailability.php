<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAvailability extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'day_of_week', 'start_time', 'end_time', 'is_available'];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public static array $days = [
        0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday',
        3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function getDayNameAttribute(): string
    {
        return self::$days[$this->day_of_week] ?? 'Unknown';
    }
}
