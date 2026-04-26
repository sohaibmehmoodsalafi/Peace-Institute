<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'curriculum', 'level',
        'price_per_session', 'monthly_price', 'sessions_per_week',
        'duration_minutes', 'thumbnail', 'is_active', 'enrolled_count',
    ];

    protected $casts = [
        'is_active'         => 'boolean',
        'price_per_session' => 'float',
        'monthly_price'     => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($course) {
            $course->slug = Str::slug($course->name);
        });
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student')
            ->withPivot('status', 'progress_percentage', 'enrolled_at', 'completed_at')
            ->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Predefined Quran courses
    public static function defaultCourses(): array
    {
        return [
            ['name' => 'Qaida',           'level' => 'beginner'],
            ['name' => 'Nazra',           'level' => 'beginner'],
            ['name' => 'Tajweed',         'level' => 'intermediate'],
            ['name' => 'Hifz',            'level' => 'advanced'],
            ['name' => 'Tafseer',         'level' => 'advanced'],
            ['name' => 'Arabic Grammar',  'level' => 'intermediate'],
        ];
    }
}
