<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Worker;
use App\Models\Overtime;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Payroll;
use App\Services\PayrollService;

class PayrollController extends Controller
{
    /* 
     * VERIFY LOGIN
     *  */
    public function verify()
    {
        return view('payroll.verify');
    }

    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'password' => 'required|string',
        ]);

        // ADMIN LOGIN
        if ($request->employee_id == 110 && $request->password === 'pw7') {
            session(['verified_worker' => 'ADMIN']);
            return redirect()->route('payroll.admin.dashboard');
        }

        // WORKER LOGIN
        $worker = Worker::where('employee_id', $request->employee_id)->first();

        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors(['Employee ID atau password salah']);
        }

        session(['verified_worker' => $worker->employee_id]);
        return redirect()->route('payroll.salary');
    }

    /* 
     * ADMIN DASHBOARD
     *  */
    public function adminDashboard(Request $request, PayrollService $service)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $payrolls = Payroll::where('month', $month)
            ->where('year', $year)
            ->get();


        // pause ⏸️
        // $payrolls = Worker::with('salaryGrade')->get()
        //     // ->map(fn($worker) => $this->calculatePayroll($worker));

        //     ->map(function ($worker) use ($service, $month, $year) {

        //         $result = $service->calculateMonthly($worker, $month, $year);

        //         return (object) array_merge([
        //             'employee_id' => $worker->employee_id,
        //             'fullname' => $worker->fullname,
        //             'role' => $worker->salaryGrade->position ?? '-',
        //         ], $result);
        //     });
        $alreadyGenerated = $payrolls->count() > 0;

        return view('payroll.admin.dashboard', compact('payrolls', 'month', 'year', 'alreadyGenerated'));
    }

    /* 
     * WORKER PAYSLIP
     *  */
    public function salary(PayrollService $service)
    {

        $employeeId = session('verified_worker');

        $worker = Worker::where('employee_id', $employeeId)
            ->with('salaryGrade')
            ->firstOrFail();

        // pause ⏸️
        // $payroll = $this->calculatePayroll($worker);
        $month = now()->month;
        $year = now()->year;

        $payroll = (object) $service->calculateMonthly($worker, $month, $year);

        return view('payroll.salary', compact('payroll', 'worker'));
    }

    public function generatePayroll(Request $request, PayrollService $service)
    {
        $month = $request->month;
        $year = $request->year;

        $workers = Worker::with('salaryGrade')->get();

        foreach ($workers as $worker) {

            if (!$worker->salary_grade_id) {
                continue;
            }

            // skip kalau payroll employee ini sudah ada
            $exists = Payroll::where('employee_id', $worker->employee_id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            if ($exists)
                continue;

            // hitung payroll
            $result = $service->calculateMonthly($worker, $month, $year);

            // simpan snapshot payroll
            Payroll::create([

                // worker snapshot
                'employee_id' => $worker->employee_id,
                'fullname' => $worker->fullname,
                'role' => $worker->salaryGrade->position ?? '-',
                'salary_grade_id' => $worker->salary_grade_id,

                // period
                'month' => $month,
                'year' => $year,

                // salary
                'basic_salary' => $result['basic_salary'],
                'fixed_allowance' => $result['fixed_allowance'],

                // attendance
                'working_days_in_month' => $result['working_days_in_month'],
                'present_days' => $result['present_days'],
                'earned_salary' => $result['earned_salary'],

                // overtime
                'overtime_hours' => $result['overtime_hours'],
                'overtime_pay' => $result['overtime_pay'],

                // tax
                'tax_deduction' => $result['tax_deduction'],

                // totals
                'total_salary' => $result['total_salary'],
                'net_salary' => $result['net_salary'],
            ]);
        }

        return back()->with('success', "Payroll berhasil digenerate untuk bulan ini!");
    }

    public function logout(Request $request)
    {
        $request->session()->forget('verified_worker');

        // Optionally, you can invalidate the session
        $request->session()->invalidate();

        // Optionally, regenerate the session token
        $request->session()->regenerateToken();

        // Redirect the user to a specific page after logout
        return redirect()->route('home')->with('message', 'You have been logged out successfully.');
    }


    /* pause ⏸️
     * TAX RULE: Flat 5%
     * Salary > 10 juta only
     *  */
    // private function calculatePPH21($basicSalary)
    // {
    //     if ($basicSalary <= 10000000) {
    //         return 0;
    //     }

    //     return round($basicSalary * 0.05);
    // }

    /* 
     * PAYROLL ENGINE FINAL
     * WITH ATTENDANCE PRORATE
     * pause ⏸️
     *  */
    // private function calculatePayroll($worker)
    // {
    //     $month = now()->month;
    //     $year = now()->year;

    //     /*  BASIC SALARY CONTRACT  */
    //     $basicSalary = $worker->salaryGrade->basic_salary ?? 0;

    //     /* 
    //      * HITUNG TOTAL HARI KERJA BULAN INI (WEEKDAY ONLY)
    //      *  */
    //     $totalWorkdays = 0;

    //     $start = now()->startOfMonth();
    //     $end = now()->endOfMonth();

    //     for ($date = $start->copy(); $date <= $end; $date->addDay()) {
    //         if (!$date->isWeekend()) {
    //             $totalWorkdays++;
    //         }
    //     }

    //     /* 
    //      * HITUNG KEHADIRAN WORKER BULAN INI
    //      *  */
    //     $presentDays = Attendance::where('employee_id', $worker->employee_id)
    //         ->whereMonth('work_date', $month)
    //         ->whereYear('work_date', $year)
    //         ->whereNotNull('clock_in_time')
    //         ->whereNotNull('clock_out_time')
    //         ->count();

    //     /* 
    //      * GAJI YANG DIDAPAT BULAN INI (PRORATE)
    //      *  */
    //     $earnedSalary = 0;

    //     if ($totalWorkdays > 0) {
    //         $earnedSalary = round(($presentDays / $totalWorkdays) * $basicSalary);
    //     }

    //     /*  OVERTIME  */
    //     $overtimes = Overtime::where('worker_id', $worker->id)
    //         ->whereMonth('overtime_date', $month)
    //         ->whereYear('overtime_date', $year)
    //         ->get();

    //     $overtimePay = $overtimes->sum('total_payment');

    //     /*  TOTAL EARNINGS (A)  */
    //     $grossSalary = $earnedSalary + $overtimePay;

    //     /*  TAX  */
    //     $taxDeduction = $this->calculatePPH21($basicSalary);

    //     /*  NET SALARY  */
    //     $netSalary = $grossSalary - $taxDeduction;

    //     return (object) [

    //         'employee_id' => $worker->employee_id,
    //         'fullname' => $worker->fullname,

    //         'position' => $worker->salaryGrade->position ?? '-',
    //         'grade_name' => $worker->salaryGrade->grade_name ?? '-',
    //         'employment_type' => $worker->salaryGrade->employment_type ?? '-',

    //         /*  CONTRACT SALARY  */
    //         'basic_salary' => $basicSalary,

    //         /*  ATTENDANCE INFO  */
    //         'total_workdays' => $totalWorkdays,
    //         'present_days' => $presentDays,

    //         /*  REAL EARNED SALARY  */
    //         'earned_salary' => $earnedSalary,

    //         /*  OVERTIME  */
    //         'overtime_pay' => $overtimePay,

    //         /*  TAX  */
    //         'tax_deduction' => $taxDeduction,

    //         /*  TOTAL  */
    //         'gross_salary' => $grossSalary,
    //         'net_salary' => $netSalary,

    //         /*  DETAIL  */
    //         'overtimes' => $overtimes,
    //     ];
    // }

    /* 
     * PAYROLL ENGINE FINAL
     * WITH Calculate BPJS
     * 
     *  */


    //pause ⏸️
    // public function closeMonth(Request $request, PayrollService $service)
    // {
    //     $month = $request->month;
    //     $year = $request->year;


    //     $workers = Worker::with('salaryGrade')->get();

    //     foreach ($workers as $worker) {

    //         // Skip jika payroll periode ini sudah ada
    //         $exists = Payroll::where('employee_id', $worker->employee_id)
    //             ->where('month', $month)
    //             ->where('year', $year)
    //             ->first();

    //         if ($exists)
    //             continue;

    //         // Hitung payroll bulan ini
    //         $result = $service->calculateMonthly($worker, $month, $year);

    //         // Simpan snapshot payroll ke database
    //         Payroll::create([

    //             // Snapshot worker
    //             'employee_id' => $worker->employee_id,
    //             'fullname' => $worker->fullname,
    //             'role' => $worker->salaryGrade->position ?? $worker->role,

    //             // Snapshot grade
    //             'salary_grade_id' => $worker->salary_grade_id,

    //             // Payroll period
    //             'month' => $month,
    //             'year' => $year,

    //             // Salary components
    //             'basic_salary' => $result['basic_salary'],
    //             'fixed_allowance' => $result['fixed_allowance'],

    //             // Attendance + prorate
    //             'working_days_in_month' => $result['working_days_in_month'],
    //             'present_days' => $result['present_days'],
    //             'earned_salary' => $result['earned_salary'],

    //             // Overtime
    //             'overtime_hours' => $result['overtime_hours'],
    //             'overtime_pay' => $result['overtime_pay'],

    //             // Tax
    //             'tax_deduction' => $result['tax_deduction'],

    //             // Total
    //             'total_salary' => $result['total_salary'],
    //             'net_salary' => $result['net_salary'],
    //         ]);
    //     }

    //     return back()->with('success', "Payroll bulan ini berhasil dibuat!");
    // }

    // public function reopenMonth(Request $request)
    // {
    //     Payroll::where('month', $request->month)
    //         ->where('year', $request->year)
    //         ->delete();

    //     return back()->with('success', 'Payroll berhasil dibuka kembali!');
    // }



}
