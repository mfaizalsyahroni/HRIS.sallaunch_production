<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Worker;
use App\Models\SalaryGrade;

class Payroll extends Model
{
    protected $table = 'payrolls';

    protected $fillable = [

        /* SNAPSHOT WORKER */
        'employee_id',
        'fullname',
        'role',

        /* SNAPSHOT SALARY GRADE */
        'salary_grade_id',

        /* PAYROLL PERIOD */
        'month',
        'year',

        /* SALARY COMPONENT */
        'basic_salary',
        'fixed_allowance',

        /* ATTENDANCE RESULT */
        'working_days_in_month',
        'present_days',
        'earned_salary',

        /* OVERTIME RESULT */
        'overtime_hours',
        'overtime_rate',
        'overtime_pay',

        /* DAILY SALARY */
        'daily_salary',

        /* BONUS / COMPENSATION */
        'thr',
        'severance_pay',
        'upmk',
        'uph',

        /* BPJS */
        'bpjs_kesehatan_employer',
        'bpjs_kesehatan_employee',

        'bpjs_jkk',
        'bpjs_jkm',

        'bpjs_jht_employer',
        'bpjs_jht_employee',

        'bpjs_jp_employer',
        'bpjs_jp_employee',


        /* DEDUCTION & TOTAL */
        'deduction',
        'total_salary',

        /* TAX & FINAL SALARY */
        'tax_deduction',
        'net_salary',
    ];

    protected $casts = [

        'basic_salary' => 'decimal:2',
        'fixed_allowance' => 'decimal:2',

        'working_days_in_month' => 'integer',
        'present_days' => 'integer',
        'earned_salary' => 'decimal:2',

        'overtime_hours' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'overtime_pay' => 'decimal:2',

        'daily_salary' => 'decimal:2',

        'thr' => 'decimal:2',
        'severance_pay' => 'decimal:2',
        'upmk' => 'decimal:2',
        'uph' => 'decimal:2',

        /* BPJS */
        'bpjs_kesehatan_employer' => 'decimal:2',
        'bpjs_kesehatan_employee' => 'decimal:2',

        'bpjs_jkk' => 'decimal:2',
        'bpjs_jkm' => 'decimal:2',

        'bpjs_jht_employer' => 'decimal:2',
        'bpjs_jht_employee' => 'decimal:2',

        'bpjs_jp_employer' => 'decimal:2',
        'bpjs_jp_employee' => 'decimal:2',


        'deduction' => 'decimal:2',
        'total_salary' => 'decimal:2',

        'tax_deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    /* 
     * RELATIONSHIP
     *  */

    // payroll → worker (pakai employee_id)
    public function worker()
    {
        return $this->belongsTo(
            Worker::class,
            'employee_id',
            'employee_id'
        );
    }

    // payroll → salary grade snapshot
    public function salaryGrade()
    {
        return $this->belongsTo(SalaryGrade::class);
    }
}
