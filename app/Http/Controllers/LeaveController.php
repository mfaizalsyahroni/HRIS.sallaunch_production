<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Worker;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LeaveController extends Controller
{
    /*
    1. FORM VERIFIKASI USER
     */
    public function create()
    {
        return view('leave.create');
    }



    public function verifyWorker(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string',
            'employee_id' => 'required|string',
            'role' => 'required|string',
            'password' => 'required|string',
        ]);

        $worker = Worker::where('employee_id', $request->employee_id)
            ->where('fullname', $request->fullname)
            ->where('role', $request->role)
            ->first();

        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return redirect()->back()->with('message1', 'Data tidak valid');
        }

        // CHECK WHETHER THERE IS ALREADY LEAVE
        $existingLeave = Leave::where('employee_id', $worker->employee_id)
            ->whereIn('status', ['pending', 'approved']) // cari yang masih aktif
            ->first();

        // If there is already leave that is pending or approved, directly direct to the show page    
        if ($existingLeave) {
            return redirect()->route('leave.show', $existingLeave->id)
                ->with('info', 'Anda sudah memiliki cuti yang belum selesai.');
        }

        // Simpan session
        session([
            'verified_worker' => [
                'fullname' => $worker->fullname,
                'employee_id' => $worker->employee_id,
                'role' => $worker->role,
            ]
        ]);

        // Admin langsung masuk ke halaman approve
        if ($worker->role === 'Admin IT HRIS') {
            return redirect()->route('leave.admin');
        }

        // User biasa
        return redirect()->route('leave.next')
            ->with('message', 'Verification successful! Please fill in leave details.');
    }


    /*
    2. FORM DETAIL CUTI
    */
    public function next()
    {
        $workerData = session('verified_worker');

        if (!$workerData) {
            return redirect()->route('leave.create')
                ->withErrors(['error' => 'Please verify first.']);

        }
        $worker = Worker::where('employee_id', $workerData['employee_id'])->first();

        return view('leave.next', compact('workerData', 'worker'));
    }


    /* 
    3. SIMPAN DATA CUTI
     */
    public function store(Request $request)
    {
        $workerData = session('verified_worker');

        if (!$workerData) {
            return redirect()->route('leave.create')
                ->withErrors(['error' => 'Session Verification is not found 404.']);
        }



        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_reason' => 'required|string',
        ]);

        $start = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');
        $end = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');

        // Hitung total hari
        $totalDays = Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;

        // Cek sisa cuti
        $worker = Worker::where('employee_id', $workerData['employee_id'])->first();

        if ($worker->leave_balance < $totalDays) {
            return redirect()->back()
                ->with('error', "Sisa cuti kamu hanya {$worker->leave_balance} hari, tidak cukup untuk {$totalDays} hari.");
        }

        $leave = Leave::create([
            'fullname' => $workerData['fullname'],
            'employee_id' => $workerData['employee_id'],
            'role' => $workerData['role'],
            'leave_type' => $request->leave_type,
            'start_date' => $start,
            'end_date' => $end,
            'total_days' => $totalDays,
            'leave_reason' => $request->leave_reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leave.show', $leave->id)
            ->with('message', 'Leave request submitted successfully! Waiting for approval.');
    }


    /* 
    4. TAMPIL DETAIL CUTI
     */
    public function show($id)
    {
        $leave = Leave::findOrFail($id);

        // I'm making sure the $worker variable exists so the Blade template doesn't throw a tantrum.
        $worker = Worker::where('employee_id', $leave->employee_id)->first();

        return view('leave.show', compact('leave', 'worker'));
    }


    /* 
    5. GENERATE PDF CUTI
     */
    public function generatePDF(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        if ($leave->status !== 'approved') {
            return redirect()->back()->with('error', 'PDF cuti hanya bisa dihasilkan jika status sudah approved.');
        }

        $size = $request->query('size', 'a4');
        $orientation = $request->query('orientation', 'portrait');

        $pdf = Pdf::loadView('leave.pdf', compact('leave'))
            ->setPaper($size, $orientation);

        return $pdf->download('surat_cuti_' . $leave->id . '.pdf');
    }


    /* 
    6. Leave Approval
     */
    public function adminIndex()
    {
        $worker = session('verified_worker');

        // Validasi role yang boleh akses adminIndex
        $allowedRoles = ['Admin IT HRIS'];

        if (!$worker || !in_array(trim($worker['role']), $allowedRoles)) {
            return redirect()->route('leave.create')
                ->withErrors(['error' => 'Please complete verification first']);
        }

        // Tentukan view sesuai tipe admin
        if ($worker['employee_id'] == 110 && trim($worker['role']) == 'Admin IT HRIS') {
            $viewFile = 'leave.leave_approver';   // halaman admin khusus pakai look1.css
        } else {
            $viewFile = 'leave.verify';  // halaman admin umum
        }

        // Add Month & year filter (default = current)
        $month = request()->query('month', Carbon::now()->format('m'));
        $year = request()->query('year', Carbon::now()->format('Y'));

        // Retrieve leaves data
        $leaves = Leave::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        // Sending data to the View using the helper function compact()
        return view($viewFile, compact('leaves', 'month', 'year'));
    }


    /* 
    7. ADMIN APPROVE / REJECT
     */
    public function approveLeave($id)
    {
        $leave = Leave::findOrFail($id);

        $worker = Worker::where('employee_id', $leave->employee_id)->first();

        if ($worker->leave_balance < $leave->total_days) {
            return redirect()->back()
                ->with('error', 'Saldo cuti tidak cukup!');
        }

        $leave->update(['status' => 'approved']);

        // Reduce leave balance with decrement (-)
        $worker->decrement('leave_balance', $leave->total_days);


        return redirect()->back()->with('acc', 'Cuti berhasil disetujui.✅');
    }

    public function rejectLeave($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'rejected']);

        return redirect()->back()->with('rejected', 'Cuti berhasil ditolak.❌');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('verified_worker');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('message', 'You have been logged out successfully.');


    }

}
