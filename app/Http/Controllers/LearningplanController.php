<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Worker;
use App\Models\LearningModule;
use App\Models\LearningProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LearningplanController extends Controller
{
    public function verify()
    {
        return view('learningplan.verify');
    }

    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'password' => 'required|string',
        ]);

        // Admin login
        if ($request->employee_id == 110 && $request->password === 'pw7') {
            session(['verified_worker' => 'ADMIN_IT_HRIS']);
            return redirect()->route('learningplan.admin.dashboard');
        }

        // Staff login
        $worker = Worker::where('employee_id', $request->employee_id)->first();
        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors(['Your employee ID or Password is wrong']);
        }

        

        session(['verified_worker' => $worker->id]);
        return redirect()->route('learningplan.staff');
    }

    public function staff()
    {
        // Proteksi session staff
        $workerSession = session('verified_worker');
        if (!$workerSession || $workerSession === 'ADMIN_IT_HRIS') {
            return redirect()->route('learningplan.verify')
                ->withErrors(['Please verify first']);
        }

        $worker = Worker::findOrFail($workerSession);

        $modules = LearningModule::orderBy('id', 'ASC')->get();

        $completedModules = LearningProgress::where('employee_id', $workerSession)
            ->where('status', 'completed')
            ->pluck('module_id')
            ->toArray();

        foreach ($modules as $module) {
            $module->completed = in_array($module->id, $completedModules);
        }

        $progress = count($completedModules) * 20;

        return view('learningplan.staff', compact('modules', 'progress'));
    }

    public function uploadFeedback(Request $request)
    {
        $workerSession = session('verified_worker');
        if (!$workerSession || $workerSession === 'ADMIN_IT_HRIS') {
            return redirect()->route('learningplan.verify')
                ->withErrors(['Please verify first']);
        }

        $request->validate([
            'module_id' => 'required|exists:learning_modules,id',
            'feedback_video' => 'required|mimes:mp4,mov,avi|max:50000',
        ]);

        $path = $request->file('feedback_video')
            ->store('feedback_videos', 'public');

        LearningProgress::create([
            'employee_id' => $workerSession,
            'module_id' => $request->module_id,
            'feedback_video' => $path,
            'status' => 'completed',
            'progress_percent' => 20,
        ]);

        return back()->with('success', 'Feedback uploaded successfully!');
    }

    public function adminDashboard(Request $request)
    {
        // Proteksi admin
        if (session('verified_worker') !== 'ADMIN_IT_HRIS') {
            return redirect()->route('learningplan.verify')
                ->withErrors(['You are not authorized']);
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'module_id' => 'nullable|exists:learning_modules,id',
                'module_name' => 'required|string',
                'category' => 'required|string',
                'youtube_id' => 'required|string',
                'duration' => 'required|string',
                'description' => 'nullable|string',
            ]);

            if ($request->module_id) {
                $module = LearningModule::find($request->module_id);
                $module->update($request->only('module_name', 'category', 'youtube_id', 'duration', 'description'));
            } else {
                LearningModule::create($request->only('module_name', 'category', 'youtube_id', 'duration', 'description'));
            }

            return redirect()->route('learningplan.admin.dashboard')
                ->with('success', 'Module saved successfully!');
        }

        $modules = LearningModule::orderBy('id', 'asc')->get();
        $totalStaff = Worker::count();
        $totalModules = LearningModule::count();
        $totalFeedback = LearningProgress::count();

        return view('learningplan.admin.dashboard', compact('modules', 'totalStaff', 'totalModules', 'totalFeedback'));
    }

    public function deleteModule($id)
    {
        if (session('verified_worker') !== 'ADMIN_IT_HRIS') {
            return redirect()->route('learningplan.verify')
                ->withErrors(['You are not authorized']);
        }

        LearningModule::destroy($id);
        return redirect()->route('learningplan.admin.dashboard')
            ->with('success', 'Module deleted successfully!');
    }

    public function Logout(Request $request)
    {
        $request->session()->forget('verified_worker');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with(['message' => 'You have been Logged Successfully']);
    }
}