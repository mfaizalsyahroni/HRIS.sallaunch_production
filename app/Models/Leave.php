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
        'password',
        'leave_types',
        'start_date',
        'end_date',
        'leave_reason',
        'status',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'employee_id', 'employee_id');
    }


    // Accessor untuk work_date: Ubah dari Y-m-d menjadi d-m-Y
    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');  // Contoh: 05-10-2023
    }

    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');  // Contoh: 05-10-2023
    }


    public function setPasswordAttribute($value)
    {
        // Jika password belum di-hash, hash sekarang
        if (!str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

}



