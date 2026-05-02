<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Teacher extends Model
{
    use HasFactory;

    /**
     * All teacher row IDs belonging to this user (covers duplicate profiles).
     * Bookings/enrollments may reference any of these IDs; hasOne teacher() only yields one row.
     */
    public static function idsForUserId(int $userId): array
    {
        return static::query()
            ->where('user_id', $userId)
            ->orderBy('id')
            ->pluck('id')
            ->all();
    }

    // Auto-filter: never return teachers with deleted/missing user
    protected static function booted(): void
    {
        static::addGlobalScope('has_user', function (Builder $builder) {
            $builder->whereHas('user');
        });
    }

    protected $fillable = [
        'user_id', 'bio', 'specialization', 'experience_years', 'hourly_rate',
        'subjects', 'education', 'certification', 'nationality', 'language',
        'gender', 'city', 'slug', 'rating', 'total_reviews', 'total_sessions', 'total_earnings',
        'pending_payout', 'status', 'is_featured',
        'monthly_salary', 'monthly_target_classes', 'documents',
    ];

    protected $casts = [
        'subjects'               => 'array',
        'hourly_rate'            => 'float',
        'total_earnings'         => 'float',
        'pending_payout'         => 'float',
        'monthly_salary'         => 'float',
        'monthly_target_classes' => 'integer',
        'is_featured'            => 'boolean',
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

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function salarySlips()
    {
        return $this->hasMany(SalarySlip::class);
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
