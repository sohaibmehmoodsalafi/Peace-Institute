<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'amount', 'status', 'payment_method',
        'payment_details', 'admin_notes', 'processed_at',
    ];

    protected $casts = [
        'amount'          => 'float',
        'payment_details' => 'array',
        'processed_at'    => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
