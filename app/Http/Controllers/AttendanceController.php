<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Worker;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $work_date = now()->toDateString();
        $employee_id = $user->employee_id;

        // Retrieve attendance records for the authenticated user for today
        $attendances = Attendance::with('worker')
            ->where('employee_id', $employee_id)
            ->where('work_date', $work_date)
            ->get();

        return view('dashboard.attendance', compact('attendances'));
    }

    public function clockIn()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // Cek apakah masih ada sesi aktif
        $openAttendance = Attendance::where('employee_id', $user->employee_id)
            ->whereDate('work_date', today())
            ->whereNull('clock_out_time')
            ->first();

        if ($openAttendance) {
            return back()->with(
                'warning',
                'You must clock out before clocking in again.'
            );
        }

        $worker = Worker::where('employee_id', $user->employee_id)->first();

        Attendance::create([
            'employee_id' => $user->employee_id,
            'work_date' => $now->toDateString(),
            'clock_in_time' => $now,
            'fullname' => $worker->fullname ?? 'N/A',
        ]);

        return back()->with(
            'message1',
            'Welcome ' . ($worker->fullname ?? 'N/A')
        );
    }

    // with total_work_hours
    public function clockOut()
    {
        $user = Auth::user();

        $attendance = Attendance::where('employee_id', $user->employee_id)
            ->whereDate('work_date', today())
            ->whereNull('clock_out_time')
            ->first();


        if (!$attendance) {
            return redirect()->back()->with('message', 'You have not clocked in today or already clocked out.');
        }

        $clockIn = Carbon::parse($attendance->clock_in_time);
        $now = now();
        $expectedClockOut = $clockIn->copy()->addHours(9);

        // Belum 9 jam → block, tampilkan alert
        if ($now->lessThan($expectedClockOut)) {
            return back()->with('error', 'You can clock out only after 8 working hours 🕘.');
        }

        // Sudah lewat 9 jam → set clock_out ke clock_in + 9 jam (bukan now())
        $clockOut = $expectedClockOut;

        $breakHours = 1;

        $effectiveHours = 9 - $breakHours;

        $totalWorkHours = min($effectiveHours, 8);

        // Update clock out time & total work hours
        $attendance->update([
            'clock_out_time' => $clockOut,
            'total_work_hours' => $totalWorkHours,
        ]);

        return back()->with('message', '"Clock out successful"');
    }

    // pending procces
    // public function clockOut()
    // {
    //     $user = Auth::user();

    //     $attendance = Attendance::where('employee_id', $user->employee_id)
    //         ->whereDate('work_date', today())
    //         ->whereNull('clock_out_time')
    //         ->first();

    //     if (!$attendance) {
    //         return redirect()->back()->with('message', 'You have not clocked in today or already clocked out.');
    //     }

    //     $clockIn = Carbon::parse($attendance->clock_in_time);
    //     $now = now();
    //     $expectedClockOut = $clockIn->copy()->addHours(9);

    //     // Belum 9 jam → block, tampilkan alert
    //     if ($now->lessThan($expectedClockOut)) {
    //         return back()->with('error', 'You can clock out only after 8 working hours 🕘.');
    //     }

    //     // Sudah lewat 9 jam → set clock_out ke clock_in + 9 jam (bukan now())
    //     $clockOut = $expectedClockOut;

    //     // Update clock out time (hapus total_work_hours karena kolom tidak ada)
    //     $attendance->update([
    //         'clock_out_time' => $clockOut,
    //         // 'total_work_hours' => $totalWorkHours, // ← dicomment, kolom tidak ada
    //     ]);

    //     return back()->with('message', '"Clock out successful"');
    // }

    public function weeklyReport($employeeId)
    {
        $startWeek = Carbon::now()->startOfWeek(); // Senin
        $endWeek = Carbon::now()->endOfWeek();   // Minggu

        $totalWeeklyHours = Attendance::where('employee_id', $employeeId)
            ->whereBetween('work_date', [$startWeek, $endWeek])
            ->sum('total_work_hours');

        return [
            'weekly_hours' => $totalWeeklyHours,
            'status' => $totalWeeklyHours >= 40 ? 'OK' : 'NOT OK'
        ];
    }


    public function logout(Request $request)
    {
        Auth::logout(); // Log out the authenticated user

        // Optionally, you can invalidate the session
        $request->session()->invalidate();

        // Optionally, regenerate the session token
        $request->session()->regenerateToken();

        // Redirect the user to a specific page after logout
        return redirect()->route('home')->with('message', 'You have been logged out successfully.');
    }
}


