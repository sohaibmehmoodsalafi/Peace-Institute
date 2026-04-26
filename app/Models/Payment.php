<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_ref', 'student_id', 'booking_id', 'amount', 'currency',
        'payment_method', 'status', 'stripe_payment_intent', 'stripe_charge_id',
        'payment_metadata', 'notes', 'paid_at',
    ];

    protected $casts = [
        'amount'           => 'float',
        'payment_metadata' => 'array',
        'paid_at'          => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($payment) {
            $payment->transaction_ref = 'TXN-'.strtoupper(Str::random(10));
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isPending(): bool   { return $this->status === 'pending'; }
}
