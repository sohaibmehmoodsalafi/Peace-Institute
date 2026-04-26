<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bio', 'specialization', 'experience_years', 'hourly_rate',
        'subjects', 'education', 'certification', 'nationality', 'language',
        'gender', 'rating', 'total_reviews', 'total_sessions', 'total_earnings',
        'pending_payout', 'status', 'is_featured',
    ];

    protected $casts = [
        'subjects'        => 'array',
        'hourly_rate'     => 'float',
        'total_earnings'  => 'float',
        'pending_payout'  => 'float',
        'is_featured'     => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilities()
    {
        return $this->hasMany(TeacherAvailability::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class);
    }

    public function earnings()
    {
        return $this->hasMany(Earning::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    // Completed sessions count
    public function completedSessions()
    {
        return $this->hasMany(ClassSession::class)->where('status', 'completed');
    }

    // Monthly earnings
    public function monthlyEarnings(int $year, int $month)
    {
        return $this->earnings()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('net_amount');
    }

    // Total hours worked
    public function totalHoursWorked(): float
    {
        return $this->classSessions()
            ->where('status', 'completed')
            ->sum('duration') / 60;
    }

    public function getNameAttribute(): string
    {
        return $this->user->name;
    }
}
