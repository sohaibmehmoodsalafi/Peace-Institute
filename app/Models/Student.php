<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date_of_birth', 'gender', 'nationality',
        'current_level', 'learning_goals', 'total_sessions',
        'completed_sessions', 'total_spent',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'total_spent'   => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student')
            ->withPivot('status', 'progress_percentage', 'enrolled_at', 'completed_at')
            ->withTimestamps();
    }

    public function upcomingBookings()
    {
        return $this->bookings()
            ->whereIn('status', ['pending', 'approved'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at');
    }

    public function getNameAttribute(): string
    {
        return $this->user->name;
    }
}
