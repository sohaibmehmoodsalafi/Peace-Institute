<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'class_session_id', 'booking_id',
        'session_duration_hours', 'hourly_rate', 'amount',
        'platform_fee', 'net_amount', 'status', 'payout_ref',
        'approved_at', 'paid_at',
    ];

    protected $casts = [
        'session_duration_hours' => 'float',
        'hourly_rate'            => 'float',
        'amount'                 => 'float',
        'platform_fee'           => 'float',
        'net_amount'             => 'float',
        'approved_at'            => 'datetime',
        'paid_at'                => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classSession()
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
