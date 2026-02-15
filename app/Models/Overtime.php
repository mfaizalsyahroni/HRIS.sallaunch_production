<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [

        // Relation
        'worker_id',

        // Snapshot
        'employee_id',
        'fullname',

        // Overtime Data
        'overtime_date',
        'start_time',
        'end_time',

        'actual_hours',
        'total_work_hours',
        'overtime_hourly_wage',
        'total_payment',

        'notes',
    ];

    // RELATIONSHIP
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    // ACCESSORS

    // Format tanggal lembur
    public function getFormattedOvertimeDateAttribute(): string
    {
        return $this->overtime_date->format('d M Y');
    }

    // Format total payment
    public function getFormattedTotalPaymentAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total_payment, 0, ',', '.');
    }

    // Format hourly wage
    public function getFormattedHourlyWageAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->overtime_hourly_wage, 0, ',', '.');
    }

    // AUTOMATIC WEEKEND CHECK
    public function getIsWeekendAttribute(): bool
    {
        return Carbon::parse($this->overtime_date)->isWeekend();
    }

    // TAMPILKAN JAM & MENIT (HR FRIENDLY)
    public function getActualHourMinuteAttribute(): string
    {
        if ($this->total_work_hours === null) {
            return '-';
        }

        $totalMinutes = round($this->total_work_hours * 60);

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return "{$hours} hours {$minutes} minutes";
    }


}

