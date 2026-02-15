<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'grade_name',
        'basic_salary',
        'salary_level',
        'employment_type',
        'is_active',
    ];

    protected $casts = [
        'basic_salary' => 'integer',
        'salary_level' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function ($salaryGrade) {
            if ($salaryGrade->basic_salary <= 6000000) {
                $salaryGrade->salary_level = 1;
            } elseif ($salaryGrade->basic_salary <= 10000000) {
                $salaryGrade->salary_level = 2;
            } else {
                $salaryGrade->salary_level = 3;
            }
        });
    }

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }


}
