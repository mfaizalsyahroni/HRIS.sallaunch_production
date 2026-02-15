<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Models\LearningProgress;
use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificationController extends Controller
{
    /* 
        LOGIN / VERIFY
     */

    public function verify()
    {
        return view('certification.verify');
    }

    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required',
        ]);

        // 1. Login Khusus Admin IT
        if ($request->employee_id == 110 && $request->password === 'pw7') {
            session([
                'verified_worker_id' => 'ADMIN',
                'verified_role' => 'ADMIN_IT_HRIS'
            ]);
            return redirect()->route('certification.admin.dashboard');
        }

        // 2. Login Khusus Management Trainee (MT)
        if ($request->employee_id == 111 && $request->password === 'pw11') {
            session([
                'verified_worker_id' => 'MT_111',
                'verified_role' => 'Management_Trainee'
            ]);
            return redirect()->route('certification.mt');
        }

        // 3. Staff Login Biasa (Cek Database)
        $worker = Worker::where('employee_id', $request->employee_id)->first();
        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors(['message' => 'Your employee ID or Password is wrong']);
        }

        session([
            'verified_worker_id' => $worker->id,
            'verified_role' => 'STAFF'
        ]);

        return redirect()->route('certification.staff');
    }

    /* 
        STAFF VIEW
     */

    public function staff()
    {
        if (!session('verified_worker_id') || session('verified_role') !== 'STAFF') {
            abort(403, 'Akses khusus Staff');
        }

        $certifications = Certification::with(['module', 'reviewer'])
            ->where('worker_id', session('verified_worker_id'))
            ->latest()
            ->get();

        return view('certification.staff', compact('certifications'));
    }

    /* 
        MT VIEW (Dulu Index)
    = */

    public function mtView()
    {
        if (session('verified_role') !== 'Management_Trainee') {
            abort(403, 'Akses khusus Management Trainee');
        }

        $pendingProgress = LearningProgress::with(['worker', 'module'])
            ->where('status', 'completed')
            ->whereDoesntHave('certification')
            ->latest()
            ->get();

        return view('certification.mt', compact('pendingProgress'));
    }

    /* 
        STORE CERTIFICATION
     */

    public function store(Request $request)
    {
        $request->validate([
            'learning_progress_id' => 'required|exists:learning_progress,id',
            'score' => 'required|in:A,B,C',
            'notes' => 'nullable|string',
        ]);

        $reviewerId = null;
        $role = session('verified_role');

        if ($role === 'Management_Trainee') {
            $reviewerId = session('verified_worker_id');
        } elseif ($role !== 'ADMIN_IT_HRIS') {
            abort(403);
        }

        $progress = LearningProgress::findOrFail($request->learning_progress_id);

        if ($progress->certification) {
            return back()->withErrors(['message' => 'Sudah dinilai sebelumnya.']);
        }

        $status = in_array($request->score, ['A', 'B','C'], true) ? 'passed' : 'failed';

        Certification::create([
            'learning_progress_id' => $progress->id,
            'worker_id' => $progress->worker_id,
            'employee_id' => $progress->employee_id,
            'module_id' => $progress->module_id,
            'score' => $request->score,
            'status' => $status,
            'notes' => $request->notes,
            'reviewed_by' => is_numeric($reviewerId) ? $reviewerId : null,
        ]);

        return back()->with('success', 'Penilaian berhasil disimpan!');
    }

    /* 
        ADMIN, PDF, & LOGOUT (Tetap)
     */

    public function adminDashboard()
    {
        if (session('verified_role') !== 'ADMIN_IT_HRIS') {
            abort(403);
        }

        $totalCertified = Certification::count();
        $totalPassed = Certification::where('status', 'passed')->count();

        // Jika Anda ingin Admin juga bisa melihat daftar pending, tambahkan ini:
        $pendingProgress = LearningProgress::with(['worker', 'module'])
            ->where('status', 'completed')
            ->whereDoesntHave('certification')
            ->latest()
            ->get();

        // Kirim SEMUA variabel yang dibutuhkan view
        return view('certification.admin.dashboard', compact(
            'totalCertified',
            'totalPassed',
            'pendingProgress'
        ));
    }

    public function download($id)
    {
        $cert = Certification::with(['worker', 'module'])
            ->where('id', $id)
            ->where('worker_id', session('verified_worker_id'))
            ->where('status', 'passed')
            ->firstOrFail();

        $pdf = Pdf::loadView('certification.pdf', compact('cert'))->setPaper('A4', 'landscape');
        return $pdf->download("Certificate-{$cert->employee_id}.pdf");
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('certification.verify')->with('message', 'Logged out');
    }
}