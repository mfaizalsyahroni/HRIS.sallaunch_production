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

    // Accessor specfically for work_date: replace from format ( Y-m-d ) to format ( d-m-Y ) for view blade
    public function getWorkDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');  // Example: 05-10-2023
    } //Eloquent Accessor (implicit invocation)
}
