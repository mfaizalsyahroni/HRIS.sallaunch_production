<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\IdeaVote;
use App\Models\IdeaReview;
use App\Models\Worker;
use App\Services\FinalScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class IdeaController extends Controller
{
    /*
    HELPER ROLE CHECK
     */
    private function requireRole($role)
    {
        if (session('verified_worker') !== $role) {
            abort(403, 'Unauthorized access');
        }
    }

    /*
    LOGIN / VERIFY
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
            session(['verified_worker' => 'ADMIN_IT_HRIS']);
            return redirect()->route('idea.admin.dashboard');
        }

        // PROGRAM INNOVATION LEAD
        if ($request->employee_id == 113 && $request->password === 'pw13') {
            session(['verified_worker' => 'PROGRAM_INNOVATION_LEAD']);
            return redirect()->route('idea.lead.dashboard');
        }

        // STAFF
        $worker = Worker::where('employee_id', $request->employee_id)->first();

        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors([
                'message' => 'Your Employee ID or Password is wrong'
            ]);
        }

        session(['verified_worker' => $worker->id]);

        return redirect()->route('idea.staff');
    }

    public function staffDashboard()
    {
        $userId = session('verified_worker');

        $myIdeas = Idea::where('user_id', $userId)
            ->latest()
            ->get();

        $votingIdeas = Idea::where('status', 'voting')
            ->withCount('votes')
            ->latest()
            ->get();

        return view('idea.staff', compact('myIdeas', 'votingIdeas'));
    }

    public function adminDashboard()
    {
        $this->requireRole('ADMIN_IT_HRIS');

        $ideas = Idea::withCount('votes')
            ->latest()
            ->get();

        return view('idea.admin.dashboard', compact('ideas'));
    }

    public function leadDashboard()
    {
        $this->requireRole('PROGRAM_INNOVATION_LEAD');

        $ideas = Idea::whereIn('status', ['voting', 'reviewed'])
            ->withCount('votes')
            ->latest()
            ->get();

        return view('idea.lead.dashboard', compact('ideas'));
    }

    /*
    IDEA DETAIL & SUBMIT
     */
    public function show(Idea $idea)
    {
        $idea->load('votes', 'reviews');
        return view('idea.show', compact('idea'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'problem' => 'required|string',
            'solution' => 'required|string',
            'impact' => 'nullable|string',
            'attachment' => 'nullable|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'demo_video' => 'nullable|mimes:mp4,mov,avi|max:20480',
        ]);

        Idea::create([
            'user_id' => session('verified_worker'),
            'title' => $request->title,
            'problem' => $request->problem,
            'solution' => $request->solution,
            'impact' => $request->impact,
            'attachment' => $request->attachment,
            'demo_video' => $request->demo_video,
            'status' => 'draft',
        ]);

        return back()->with('success', 'Idea berhasil disubmit.');
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
        $userId = $this->getWorkerId();

        if (!$userId) {
            return back()->with('error', 'Hanya staff yang bisa voting.');
        }

        if ($idea->status !== 'voting') {
            return back()->with('error', 'Voting belum dibuka.');
        }

        IdeaVote::firstOrCreate([
            'idea_id' => $idea->id,
            'user_id' => $userId,
        ]);

        return back()->with('success', 'Vote berhasil.');
    }

    /*
    REVIEW & SCORING
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
            [
                'idea_id' => $idea->id,
            ],
            [
                'reviewer_id' => -1, // Program Innovation Lead
                'business_impact' => $request->business_impact,
                'feasibility' => $request->feasibility,
                'sustainability' => $request->sustainability,
                'notes' => $request->notes,
            ]
        );

        $idea->update(['status' => 'reviewed']);

        return back()->with('success', 'Scoring berhasil disimpan.');
    }

    /*
    FINAL RESULT
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

    private function getWorkerId()
    {
        $id = session('verified_worker');
        return is_numeric($id) ? $id : null;
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $type = $request->type;

        if ($type == 'image') {
            $path = $file->store('editor/images', 'public');
        }

        if ($type == 'video') {
            $path = $file->store('editor/videos', 'public');
        }

        return response()->json([
            'url' => asset('storage/' . $path)
        ]);
    }


    public function logout(Request $request)
    {
        $request->session()->forget('verified_worker');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home')->with(['message' => "Logout Succesfully"]);
    }
}