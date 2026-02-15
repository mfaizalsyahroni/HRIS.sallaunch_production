<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'work_date',
        'clock_in_time',
        'clock_out_time',
        'fullname',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'employee_id', 'employee_id');
    }

    // Accessor untuk work_date: Ubah dari Y-m-d menjadi d-m-Y
    public function getWorkDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');  // Contoh: 05-10-2023
    }
}
