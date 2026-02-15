<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Worker;
use App\Models\Suggestion;
use App\Models\SuggestionFeedback;

class EmployeeSuggestionController extends Controller
{
    /* =====================
       AUTH FORM
    ===================== */
    public function verifyForm()
    {
        return view('suggestions.verify');
    }

    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required'
        ]);

        // ADMIN IT HRIS
        if ($request->employee_id === '110' && $request->password === 'pw7') {
            session(['verified_worker' => 'ADMIN_IT_HRIS']);
            return redirect()->route('suggestions.admin.index');
        }

        // WORKER
        $worker = Worker::where('employee_id', $request->employee_id)->first();
        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors(['message' => 'Login gagal']);
        }

        session(['verified_worker' => $worker->employee_id]);
        return redirect()->route('suggestions.index');
    }

    /* =====================
       WORKER
    ===================== */
    public function index()
    {
        if (!session('verified_worker') || session('verified_worker') === 'ADMIN_IT_HRIS') {
            abort(403);
        }

        return view('suggestions.form');
    }

    public function store(Request $request)
    {
        if (!session('verified_worker') || session('verified_worker') === 'ADMIN_IT_HRIS') {
            abort(403);
        }

        $request->validate([
            'category' => 'required',
            'title' => 'required',
            'description' => 'required',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,mp4,mov,avi'
        ]);

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('suggestions', 'public');

            $attachmentType = str_starts_with(
                $file->getMimeType(),
                'video'
            ) ? 'video' : 'image';
        }

        Suggestion::create([
            'employee_id' => session('verified_worker'),
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'new',
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType
        ]);

        return back()->with('success', 'Suggestion berhasil dikirim');
    }


    /* =====================
       ADMIN
    ===================== */
    public function adminIndex()
    {
        if (session('verified_worker') !== 'ADMIN_IT_HRIS') {
            abort(403);
        }

        $suggestions = Suggestion::with('worker', 'feedbacks')
            ->latest()
            ->get();

        return view('suggestions.admin.index', compact('suggestions'));
    }

    public function feedback(Request $request, $id)
    {
        if (session('verified_worker') !== 'ADMIN_IT_HRIS') {
            abort(403);
        }

        $request->validate([
            'feedback' => 'required'
        ]);

        SuggestionFeedback::create([
            'suggestion_id' => $id,
            'admin_employee_id' => 110,
            'feedback' => $request->feedback
        ]);

        Suggestion::where('id', $id)->update([
            'status' => 'resolved'
        ]);

        return back()->with('success', 'Feedback terkirim');
    }
}
