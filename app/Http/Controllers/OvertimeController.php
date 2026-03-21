<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Overtime;
use App\Models\Worker;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OvertimeController extends Controller
{
    /// VERIFY (LOGIN MANUAL)
    public function verify()
    {
        return view('overtime.verify');
    }

    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'password' => 'required|string',
        ]);

        // ADMIN
        if ($request->employee_id == 110 && $request->password === 'pw7') {
            session(['verified_worker' => 'ADMIN_IT_HRIS']);
            return redirect()->route('overtime.admin.dashboard');
        }

        // WORKER
        $worker = Worker::where('employee_id', $request->employee_id)->first();

        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors([
                'message' => 'Employee ID atau password salah'
            ]);
        }

        session(['verified_worker' => $worker->employee_id]);
        return redirect()->route('overtime.list');
    }

    private function checkAdmin(): void
    {
        if (session('verified_worker') !== 'ADMIN_IT_HRIS') {
            abort(403, 'ADMIN ONLY');
        }
    }

    // ADMIN DASHBOARD

    // public function adminDashboard()
    // {
    //     $this->checkAdmin();

    //     $totalOvertimes = Overtime::count();
    //     $totalPayment = Overtime::sum('total_payment');

    //     $overtimes = Overtime::with('worker')
    //         ->latest()
    //         ->limit(10)
    //         ->get();

    //     return view('overtime.admin.dashboard', compact(
    //         'totalOvertimes',
    //         'totalPayment',
    //         'overtimes'
    //     ));
    // }


    // LIST OVERTIME

    // public function listOvertime()
    // {


    //     $session = session('verified_worker');

    //     $query = Overtime::with('worker')->latest();

    //     $worker = null;

    //     if ($session !== 'ADMIN_IT_HRIS') {
    //         $query->where('worker_id', $session);
    //         $worker = Worker::find($session);
    //     }

    //     return view('overtime.listovertime', [
    //         'overtimes' => $query->get(),
    //         'worker' => $worker
    //     ]);
    // }


    public function adminDashboard(Request $request)
    {
        $this->checkAdmin();

        $query = Overtime::with('worker')->latest();

        if ($request->filled('worker_id')) {
            $query->where('worker_id', $request->worker_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('overtime_date', $request->date);
        }
        
        return view('overtime.admin.dashboard', [
            'overtimes' => $query->get(),
            'workers' => Worker::orderBy('fullname')->get(),
            'isAdmin' => true,
        ]);
    }


    public function listOvertime(Request $request)
    {
        $session = session('verified_worker');

        $query = Overtime::with('worker')->latest();

        $workers = collect();
        $selectedWorker = null;
        $worker = null;

        // admin
        if ($session === 'ADMIN_IT_HRIS') {
            $workers = Worker::orderBy('fullname')->get();

            if ($request->filled('worker_id')) {
                $selectedWorker = $request->worker_id;
                $query->where('worker_id', $request->worker_id);
            }
        }

        //worker 
        else {
            // FIX: cari by employee_id, bukan id
            $worker = Worker::where('employee_id', $session)->first();
            if ($worker) {
                $query->where('worker_id', $worker->id); // FIX: pakai $worker->id
            }
        }
        return view('overtime.listovertime', [
            'overtimes' => $query->get(),
            'workers' => $workers,
            'worker' => $worker,
            'selectedWorker' => $selectedWorker
        ]);

    }

    // START OVERTIME
    public function start()
    {
        // FIX: find by employee_id
        $worker = Worker::with('salaryGrade')
            ->where('employee_id', session('verified_worker'))
            ->firstOrFail();

        // Prevent double overtime
        $active = Overtime::where('worker_id', $worker->id)
            ->whereNull('end_time')
            ->first();

        if ($active) {
            return back()->withErrors(['Overtime is still running']);
        }

        $today = now()->toDateString();
        $isWeekend = Carbon::parse($today)->isWeekend();

        // IF weekday → required attendance 8 hours
        if (!$isWeekend) {
            $attendance = Attendance::where('employee_id', $worker->employee_id)
                ->where('work_date', $today)
                ->whereNotNull('clock_out_time')
                ->first();

            if (!$attendance || $attendance->total_work_hours < 8) {
                return back()->withErrors([
                    'message' => 'Overtime weekday hanya bisa dimulai setelah kerja minimal 8 jam'
                ]);
            }
        }

        // Weekend or weekday valid → can start
        Overtime::create([
            'worker_id' => $worker->id,
            'employee_id' => $worker->employee_id,
            'fullname' => $worker->fullname,
            'overtime_date' => $today,
            'start_time' => now()->format('H:i:s'),
        ]);

        return back()->with('success', 'Overtime dimulai');
    }



    //FINISH OVERTIME
    public function finish()
    {
        $worker = Worker::with('salaryGrade')
            ->where('employee_id', session('verified_worker'))
            ->firstOrFail();

        $overtime = Overtime::where('worker_id', $worker->id)
            ->whereNull('end_time')
            ->firstOrFail();

        // dd([
        //     'worker_id' => $worker->id,
        //     'employee_id' => $worker->employee_id,
        //     'salary_grade_id' => $worker->salary_grade_id,
        //     'salaryGrade' => $worker->salaryGrade,
        // ]);



        $end = now();
        $dateOnly = Carbon::parse($overtime->overtime_date)->format('Y-m-d'); // '2026-03-09'

        $start = Carbon::parse($dateOnly . ' ' . $overtime->start_time);   // '2026-03-09 04:16:23'
        $endFull = Carbon::parse($dateOnly . ' ' . $end->format('H:i:s'));



        // Handle kasus lembur lewat tengah malam
        if ($endFull->lt($start)) {
            $endFull->addDay();
        }

        // Calculate actual overtime hours (raw, in decimal)
        $actualHours = round($start->diffInMinutes($endFull) / 60, 2);

        // Paid hours: floored to nearest 0.5 per HR policy (e.g. 2.33 → 2.0)
        $totalHours = $this->floorToHalfHour($actualHours);

        // Base hourly wage: monthly salary ÷ 173 working hours
        $hourlyWage = round($worker->salaryGrade->basic_salary / 173, 2);

        // Check if overtime falls on a weekend (different rate applies)
        $isWeekend = Carbon::parse($overtime->overtime_date)->isWeekend();


        // Weekday → wajib attendance 8 jam
        if (!$isWeekend) {
            $attendance = Attendance::where('employee_id', $worker->employee_id)
                ->where('work_date', $overtime->overtime_date)
                ->whereNotNull('clock_out_time')
                ->first();
            if (!$attendance || $attendance->total_work_hours < 8) {
                return back()->withErrors(['Overtime weekday hanya bisa setelah kerja minimal 8 jam']);
            }
        }

        // Total payment
        // Total payment sesuai PP 35/2021
        if ($isWeekend) {
            // Hari libur/weekend:
            // Jam 1-8  → 2× upah/jam
            // Jam ke-9 → 3× upah/jam
            // Jam 10+  → 4× upah/jam
            if ($totalHours <= 8) {
                $totalPayment = $totalHours * 2 * $hourlyWage;
            } elseif ($totalHours <= 9) {
                $totalPayment = (8 * 2 * $hourlyWage)
                    + (($totalHours - 8) * 3 * $hourlyWage);
            } else {
                $totalPayment = (8 * 2 * $hourlyWage)
                    + (1 * 3 * $hourlyWage)
                    + (($totalHours - 9) * 4 * $hourlyWage);
            }
        } else {
            // Hari kerja (weekday):
            // Jam ke-1  → 1.5× upah/jam
            // Jam ke-2+ → 2× upah/jam
            if ($totalHours <= 1) {
                $totalPayment = $totalHours * 1.5 * $hourlyWage;
            } else {
                $totalPayment = (1 * 1.5 * $hourlyWage)
                    + (($totalHours - 1) * 2 * $hourlyWage);
            }
        }

        // Update record overtime
        $overtime->update([
            'end_time' => $end->format('H:i:s'),
            'actual_hours' => $actualHours,
            'total_work_hours' => $totalHours,
            'overtime_hourly_wage' => $hourlyWage,
            'total_payment' => round($totalPayment, 2),
        ]);

        return back()->with('success', 'Finish Overtime');
    }



    private function floorToHalfHour(float $hours): float
    {
        return floor($hours * 2) / 2;
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




    /* 
    Option New Fiture 
    General rules in Indonesia: 
    - Max 4 hours / day 
    - Max 18 hours / week
    */

}