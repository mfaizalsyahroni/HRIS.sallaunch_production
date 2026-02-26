<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\IdeaVote;
use App\Models\IdeaReview;
use App\Models\Worker;
use App\Services\FinalScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class IdeaController extends Controller
{
    /*
    ROLE GUARD
    */
    private function requireRole(string $role)
    {
        if (session('worker_role') !== $role) {
            abort(403, 'Unauthorized access');
        }
    }

    /*
    LOGIN
    */
    public function verify()
    {
        return view('idea.verify');
    }

    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required',
        ]);

        // ADMIN HRIS
        if ($request->employee_id == 110 && $request->password === 'pw7') {
            session([
                'worker_role' => 'ADMIN_IT_HRIS',
                'worker_id' => null
            ]);

            return redirect()->route('idea.admin.dashboard');
        }

        // PROGRAM LEAD
        if ($request->employee_id == 113 && $request->password === 'pw13') {
            session([
                'worker_role' => 'PROGRAM_INNOVATION_LEAD',
                'worker_id' => null
            ]);

            return redirect()->route('idea.lead.dashboard');
        }

        // STAFF
        $worker = Worker::where('employee_id', $request->employee_id)->first();

        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors([
                'message' => 'Employee ID or password invalid'
            ]);
        }

        session([
            'worker_role' => 'STAFF',
            'worker_id' => $worker->id
            // 'worker_id' => $worker->employee_id
        ]);

        return redirect()->route('idea.staff');
    }

    // public function staffDashboard()
    // {
    //     $workerId = session('worker_id');

    //     $myIdeas = Idea::where('user_id', $workerId)->latest()->get();

    //     $votingIdeas = Idea::where('status', 'voting')
    //         ->withCount('votes')
    //         ->latest()
    //         ->get();

    //     return view('idea.staff', compact('myIdeas', 'votingIdeas'));
    // }

    public function staffDashboard()
    {
        $workerId = session('worker_id');


        $myIdeas = Idea::with('worker')
            ->where('user_id', $workerId)
            ->latest()
            ->get();


        $votingIdeas = Idea::with('worker')
            ->where('status', 'voting')
            ->where('user_id', '!=', $workerId)
            ->withCount('votes')
            ->latest()
            ->get();

        return view('idea.staff', compact('myIdeas', 'votingIdeas'));
    }

    public function adminDashboard()
    {
        $this->requireRole('ADMIN_IT_HRIS');

        $ideas = Idea::withCount('votes')->latest()->get();

        return view('idea.admin.dashboard', compact('ideas'));
    }

    public function leadDashboard()
    {
        $this->requireRole('PROGRAM_INNOVATION_LEAD');

        $ideas = Idea::whereIn('status', ['voting', 'reviewed'])
            ->with('worker') // load fullname + employee_id
            ->withCount('votes')
            ->latest()
            ->get();

        return view('idea.lead.dashboard', compact('ideas'));
    }


    // public function show(Idea $idea)
    // {
    //     $idea->load('votes', 'reviews');

    //     return view('idea.show', compact('idea'));
    // }

    public function show(Idea $idea)
    {
        $idea->load('votes', 'reviews');

        $fileUrl = $idea->attachment ? asset('storage/' . $idea->attachment) : null;
        $fileExtension = $idea->attachment ? strtolower(pathinfo($idea->attachment, PATHINFO_EXTENSION)) : null;

        return view('idea.show', compact('idea', 'fileUrl', 'fileExtension'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'problem' => 'required|string',
            'solution' => 'required|string',
            'impact' => 'nullable|string',
            'attachment' => 'nullable|file|max:51200',
            'demo_video' => 'nullable|file|max:204800',
        ]);

        $attachmentPath = null;
        $videoPath = null;

        // ✅ check extension manual
        if ($request->hasFile('attachment')) {

            $file = $request->file('attachment');

            $filename = time() . '_' . $file->getClientOriginalName();

            $attachmentPath = $file->storeAs('attachments', $filename, 'public');
        }

        if ($request->hasFile('demo_video')) {
            $videoPath = $request->file('demo_video')->store('videos', 'public');
        }

        Idea::create([
            'user_id' => session('worker_id'),
            'title' => $request->title,
            'problem' => $request->problem,
            'solution' => $request->solution,
            'impact' => $request->impact,
            'attachment' => $attachmentPath,
            'demo_video' => $videoPath,
            'status' => 'voting',
        ]);

        return redirect()->route('idea.staff')
            ->with('success', 'Idea submitted successfully.');
    }

    // public function downloadAttachment(Idea $idea)
    // {
    //     return Storage::disk('public')->download($idea->attachment);

    // }


    // public function downloadAttachment(Idea $idea)
    // {
    //     $path = storage_path('app/public/' . $idea->attachment);

    //     if (request()->has('download')) {
    //         return response()->download($path);
    //     }

    //     return response()->file($path);
    // }


    public function downloadAttachment(Idea $idea)
    {
        $path = storage_path('app/public/' . $idea->attachment);

        if (!file_exists($path)) {
            abort(404);
        }

        $filename = basename($path);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (request()->has('download')) {
            return response()->download($path, $filename);
        }

        $previewable = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($extension, $previewable)) {
            return response()->file($path);
        }

        $office = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

        if (in_array($extension, $office)) {
            $url = asset('storage/' . $idea->attachment);

            return redirect()->away(
                'https://view.officeapps.live.com/op/view.aspx?src=' . urlencode($url)
            );
        }

        return response()->download($path, $filename);
    }

    public function publish(Idea $idea)
    {
        $idea->update(['status' => 'voting']);

        return back()->with('success', 'Idea masuk fase voting.');
    }

    /*
    VOTING
    */
    public function vote(Idea $idea)
    {
        $workerId = session('worker_id');

        if (!$workerId) {
            return back()->with('error', 'Only staff can vote.');
        }

        if ($idea->status !== 'voting') {
            return back()->with('error', 'Voting not open.');
        }

        // You are not allowed to vote for your own idea
        if ($idea->user_id == $workerId) {
            return back()->with('error', 'You cannot vote your own idea.');
        }

        IdeaVote::firstOrCreate([
            'idea_id' => $idea->id,
            'worker_id' => session('worker_id'),
        ]);

        return back()->with('vote', 'Vote recorded.');
    }

    /*
    REVIEW
    */
    public function review(Request $request, Idea $idea)
    {
        $this->requireRole('PROGRAM_INNOVATION_LEAD');

        $request->validate([
            'business_impact' => 'required|integer|min:1|max:5',
            'feasibility' => 'required|integer|min:1|max:5',
            'sustainability' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        IdeaReview::updateOrCreate(
            ['idea_id' => $idea->id],
            [
                'reviewer_id' => session('worker_id'),
                'business_impact' => $request->business_impact,
                'feasibility' => $request->feasibility,
                'sustainability' => $request->sustainability,
                'notes' => $request->notes,
            ]
        );

        $idea->update(['status' => 'reviewed']);

        return back()->with('success', 'Scoring saved.');
    }

    /*
    RESULT
    */
    public function result(Idea $idea, FinalScoreService $scoring)
    {
        $finalScore = $scoring->calculate($idea);

        return view('idea.result', compact('idea', 'finalScore'));
    }

    public function winner(FinalScoreService $scoring)
    {
        $this->requireRole('PROGRAM_INNOVATION_LEAD');

        $ranking = $scoring->getRanking();
        $winner = $scoring->getWinner();
        $top3 = $scoring->getTop(3);

        return view('idea.winner', compact('ranking', 'winner', 'top3'));
    }

    /*
    FILE UPLOAD
    */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $type = $request->type;

        if ($type === 'image') {
            $path = $file->store('editor/images', 'public');
        } else {
            $path = $file->store('editor/videos', 'public');
        }

        return response()->json([
            'url' => asset('storage/' . $path)
        ]);
    }

    /*
    LOGOUT
    */
    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('home')
            ->with('message', 'Logout successfully');
    }
}