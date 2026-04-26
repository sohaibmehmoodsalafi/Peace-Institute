<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'teacher_id', 'student_id',
        'started_at', 'ended_at', 'duration', 'status',
        'earned_amount', 'hourly_rate_snapshot', 'session_notes', 'earning_processed',
    ];

    protected $casts = [
        'started_at'           => 'datetime',
        'ended_at'             => 'datetime',
        'earned_amount'        => 'float',
        'hourly_rate_snapshot' => 'float',
        'earning_processed'    => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function earning()
    {
        return $this->hasOne(Earning::class);
    }

    // Calculate earned amount: (duration / 60) * hourly_rate
    public function calculateEarning(): float
    {
        return round(($this->duration / 60) * $this->hourly_rate_snapshot, 2);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getDurationHoursAttribute(): float
    {
        return round($this->duration / 60, 2);
    }
}
