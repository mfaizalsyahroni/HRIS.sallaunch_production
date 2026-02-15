<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PersonalInfo extends Model
{
    protected $table = 'personal_infos';

    protected $fillable = [
        'photo',
        'employee_id',
        'fullname',
        'nickname',
        'gender',
        'birth_place',
        'birth_date',
        'marital_status',
        'nationality',
        'religion',
        'nik',
        'kk_number',
        'passport_number',
        'npwp',
        'bpjs_health',
        'bpjs_employment',
        'address_current',
        'address_ktp',
        'postal_code',
        'phone',
        'phone_emergency',
        'email_personal',
        'emergency_contact_name',
        'emergency_contact_relation',
        'join_date',
        'employment_status',
        'department',
        'role',
        'blood_type',
        'shirt_size',
        'notes',
    ];

    //Relation One to Many
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'employee_id', 'employee_id');
    }

    public function getRoleAttribute($value)
    {
        // Kalau role di personal_infos kosong, ambil dari worker
        return $value ?? $this->worker?->role;
    }

    public function getBirthDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');  // Contoh: 05-10-2023
    }

    public function getJoinDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');  // Contoh: 05-10-2023
    }


}