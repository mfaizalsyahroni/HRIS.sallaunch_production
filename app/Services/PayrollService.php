<?php

namespace App\Services;

use App\Models\Worker;
use App\Models\Attendance;
use App\Models\Overtime;
use Carbon\Carbon;

class PayrollService
{
    public function calculateMonthly(Worker $worker, int $month, int $year): array
    {
        $basicSalary = $worker->salaryGrade->basic_salary ?? 0;
        $fixedAllowance = $worker->salaryGrade->fixed_allowance ?? 0;

        // total weekday dalam bulan
        $totalWorkdays = 0;
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            if (!$date->isWeekend())
                $totalWorkdays++;
        }

        // attendance hadir
        $presentDays = Attendance::where('employee_id', $worker->employee_id)
            ->whereMonth('work_date', $month)
            ->whereYear('work_date', $year)
            ->whereNotNull('clock_in_time')
            ->whereNotNull('clock_out_time')
            ->count();

        // prorate salary
        $earnedSalary = $totalWorkdays > 0
            ? round(($presentDays / $totalWorkdays) * $basicSalary, 2)
            : 0;

        // overtime bulan ini
        $overtimes = Overtime::where('worker_id', $worker->id)
            ->whereMonth('overtime_date', $month)
            ->whereYear('overtime_date', $year)
            ->get();

        $overtimeHours = $overtimes->sum('total_work_hours');
        $overtimePay = $overtimes->sum('total_payment');

        // gross salary
        $grossSalary = $earnedSalary + $fixedAllowance + $overtimePay;

        // PAUSE ⏸️ tax sederhana
        // $tax = $basicSalary > 10000000 ? round($basicSalary * 0.05, 2) : 0;

        // tax dihitung dari gross salary
        $tax = $basicSalary > 10000000
            ? round($grossSalary * 0.05, 2)
            : 0;

        $netSalary = $grossSalary - $tax;

        // $netSalary = $grossSalary - $tax;

        return [

            'fullname' => $worker->fullname,
            'position' => $worker->salaryGrade->position ?? '-',
            'grade_name' => $worker->salaryGrade->grade_name ?? '-',
            'employment_type' => $worker->salaryGrade->employment_type ?? '-',

            'basic_salary' => $basicSalary,
            'fixed_allowance' => $fixedAllowance,

            'working_days_in_month' => $totalWorkdays,
            'present_days' => $presentDays,
            'overtimes' => $overtimes,

            'earned_salary' => $earnedSalary,

            'overtime_hours' => $overtimeHours,
            'overtime_pay' => $overtimePay,

            'tax_deduction' => $tax,
            'total_salary' => $grossSalary,
            'net_salary' => $netSalary,
        ];
    }
}
