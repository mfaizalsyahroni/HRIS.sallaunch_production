<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class Leave extends Model
{

    use HasFactory;
    protected $fillable = [
        'fullname',
        'employee_id',
        'role',
        // 'password',
        'leave_type',
        'start_date',
        'end_date',
        'total_days', //point
        'leave_reason',
        'status',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'employee_id', 'employee_id');
    }


    // Accessor for work_date: Change from Y-m-d to d-m-Y
    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');  // Example: 05-10-2023
    }

    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');  // Example: 05-10-2023
    }

    // Raw date untuk kalkulasi (tanpa accessor)
    public function getRawStartDate()
    {
        return $this->getRawOriginal('start_date');
    }

    public function getRawEndDate()
    {
        return $this->getRawOriginal('end_date');
    }

    public function setPasswordAttribute($value)
    {
        // If the password has not been the hashed?, hash it now! W::bcryprt($value)
        if (!str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

}



