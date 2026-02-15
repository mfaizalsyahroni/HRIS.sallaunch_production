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

        return view('leave.next', compact('workerData'));
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
            'leave_types' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_reason' => 'required|string',
        ]);

        $start = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');
        $end = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');

        $leave = Leave::create([
            'fullname' => $workerData['fullname'],
            'employee_id' => $workerData['employee_id'],
            'role' => $workerData['role'],
            'password' => '-',
            'leave_types' => $request->leave_types,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'leave_reason' => $request->leave_reason,
            'status' => 'pending',
        ]);

        session()->forget(['verified_worker']);

        return redirect()->route('leave.show', $leave->id)
            ->with('message', 'Leave request submitted successfully! Waiting for approval.');
    }


    /* 
    4. TAMPIL DETAIL CUTI
     */
    public function show($id)
    {
        $leave = Leave::findOrFail($id);
        return view('leave.show', compact('leave'));
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
    6. HALAMAN ADMIN KHUSUS APPROVAL CUTI
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

        // Ambil data cuti
        $leaves = Leave::orderBy('created_at', 'desc')->get();

        // Tampilkan view yang sesuai
        return view($viewFile, compact('leaves'));
    }


    /* 
    7. ADMIN APPROVE / REJECT
     */
    public function approveLeave($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'approved']);

        session()->forget('verified_worker');

        return redirect()->back()->with('success', 'Cuti berhasil disetujui.✅');
    }

    public function rejectLeave($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'rejected']);

        session()->forget('verified_worker');

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
