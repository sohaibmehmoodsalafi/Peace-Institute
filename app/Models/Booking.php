<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_ref', 'student_id', 'teacher_id', 'course_id',
        'scheduled_at', 'duration', 'status', 'booking_type', 'amount',
        'meeting_link', 'meeting_id', 'student_notes', 'teacher_notes',
        'cancellation_reason', 'approved_at', 'cancelled_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'approved_at'  => 'datetime',
        'cancelled_at' => 'datetime',
        'amount'       => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            $booking->booking_ref = 'PI-'.date('Y').'-'.strtoupper(Str::random(6));
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        // Bookings must resolve the linked teacher row even if global scope would hide it.
        return $this->belongsTo(Teacher::class)->withoutGlobalScope('has_user');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function classSession()
    {
        return $this->hasOne(ClassSession::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function scopePending($query)   { return $query->where('status', 'pending'); }
    public function scopeApproved($query)  { return $query->where('status', 'approved'); }
    public function scopeCompleted($query) { return $query->where('status', 'completed'); }

    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isApproved(): bool   { return $this->status === 'approved'; }
    public function isCompleted(): bool  { return $this->status === 'completed'; }
    public function isCancelled(): bool  { return $this->status === 'cancelled'; }

    public function getScheduledAtLocalAttribute(): string
    {
        return $this->scheduled_at->format('D, M d Y \a\t h:i A');
    }
}
