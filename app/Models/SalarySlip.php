<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'month', 'year',
        'fixed_salary', 'target_classes',
        'conducted_classes', 'missed_classes',
        'deduction_per_class', 'total_deduction',
        'admin_adjustment', 'adjustment_note',
        'net_salary', 'status',
        'approved_at', 'paid_at', 'approved_by',
    ];

    protected $casts = [
        'approved_at'       => 'datetime',
        'paid_at'           => 'datetime',
        'fixed_salary'      => 'float',
        'deduction_per_class' => 'float',
        'total_deduction'   => 'float',
        'admin_adjustment'  => 'float',
        'net_salary'        => 'float',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getPeriodAttribute(): string
    {
        return date('F Y', mktime(0, 0, 0, $this->month, 1, $this->year));
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'approved',
            'paid'     => 'completed',
            default    => 'pending',
        };
    }

    public function recalculate(): void
    {
        $perClass   = $this->target_classes > 0 ? $this->fixed_salary / $this->target_classes : 0;
        $deduction  = $this->missed_classes * $perClass;
        $net        = $this->fixed_salary - $deduction + $this->admin_adjustment;

        $this->update([
            'deduction_per_class' => $perClass,
            'total_deduction'     => $deduction,
            'net_salary'          => max(0, $net),
        ]);
    }
}
