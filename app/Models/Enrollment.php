<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'teacher_id', 'course_id',
        'selected_days', 'classes_per_week',
        'preferred_time', 'monthly_fee',
        'start_date', 'status', 'notes',
        'admin_note', 'approved_at', 'approved_by',
    ];

    protected $casts = [
        'selected_days' => 'array',
        'monthly_fee'   => 'float',
        'start_date'    => 'date',
        'approved_at'   => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────
    public function student()    { return $this->belongsTo(Student::class); }
    public function teacher()    { return $this->belongsTo(Teacher::class); }
    public function course()     { return $this->belongsTo(Course::class); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }

    // ── Accessors ─────────────────────────────────────────────────
    public function getDaysLabelAttribute(): string
    {
        $days = $this->selected_days ?? [];
        return implode(', ', array_map('ucfirst', $days));
    }

    public function getClassesPerMonthAttribute(): int
    {
        return ($this->classes_per_week ?? 0) * 4;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active'    => 'background:rgba(52,211,153,.15);color:#34d399;border:1px solid rgba(52,211,153,.3)',
            'pending'   => 'background:rgba(251,191,36,.15);color:#fbbf24;border:1px solid rgba(251,191,36,.3)',
            'paused'    => 'background:rgba(156,163,175,.15);color:#9ca3af;border:1px solid rgba(156,163,175,.3)',
            'cancelled' => 'background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3)',
            default     => '',
        };
    }
}
