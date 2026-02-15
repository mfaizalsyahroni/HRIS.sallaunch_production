<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyOption;
use App\Models\SurveySubmission;
use App\Models\SurveyAnswer;
use App\Models\Worker;

class SurveyController extends Controller
{
    /* =====================
        AUTH
    ===================== */
    public function verifyForm()
    {
        return view('survey.admin.verify');
    }

    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required'
        ]);

        // ADMIN
        if ($request->employee_id === '110' && $request->password === 'pw7') {
            session(['verified_worker' => 'ADMIN_IT_HRIS']);
            return redirect()->route('survey.dashboard');
        }

        // WORKER
        $worker = Worker::where('employee_id', $request->employee_id)->first();
        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors(['message' => 'Login gagal']);
        }

        session(['verified_worker' => $worker->employee_id]);


        $survey = Survey::where('is_active', true)->first();


        if (!$survey) {
            return back()->withErrors([
                'message' => 'No active survey available at the moment.'
            ]);
        }


        return redirect()->route('survey.form', $survey->id);
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('survey.admin.verify');
    }

    private function checkAdmin()
    {
        if (session('verified_worker') !== 'ADMIN_IT_HRIS') {
            abort(403, 'ADMIN');
        }
    }

    /* =====================
        ADMIN
    ===================== */
    public function dashboard()
    {
        $this->checkAdmin();

        $surveys = Survey::latest()->paginate(20);

        return view('survey.admin.dashboard', compact('surveys'));
    }

    public function createSurvey(Request $request)
    {
        $this->checkAdmin();


        $validated = $request->validate([
            'survey_name' => 'required|string|max:255'
        ]);

        Survey::create([
            'survey_name' => $validated['survey_name'],
            'is_active' => true
        ]);

        return back()->with('success', 'Survey berhasil dibuat');
    }

    public function questions($survey_id)
    {
        $this->checkAdmin();
        $survey = Survey::with('questions.options')->findOrFail($survey_id);
        return view('survey.admin.questions', compact('survey'));
    }

    public function deleteSurvey($id)
    {
        $this->checkAdmin();
        Survey::findOrFail($id)->delete();
        return back();
    }


    public function addQuestion(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'survey_id' => 'required',
            'question' => 'required',
            'type' => 'required'
        ]);

        $q = SurveyQuestion::create([
            'survey_id' => $request->survey_id,
            'question' => $request->question,
            'type' => $request->type
        ]);

        if (in_array($request->type, ['radio', 'checkbox']) && $request->options) {
            foreach ($request->options as $opt) {
                if (!empty(trim($opt))) {
                    SurveyOption::create([
                        'question_id' => $q->id,
                        'option_text' => trim($opt)
                    ]);
                }
            }
        }


        return back()->with('success', 'Pertanyaan berhasil ditambahkan');
    }


    public function editQuestion(Request $request, $id)
    {
        $this->checkAdmin();

        $question = SurveyQuestion::findOrFail($id);

        if ($request->isMethod('post')) {

            $request->validate([
                'question' => 'required',
                'type' => 'required'
            ]);

            $question->update([
                'question' => $request->question,
                'type' => $request->type
            ]);

            return view('survey.admin.edit_question', compact('question'))
                ->with('success', 'Pertanyaan berhasil diupdate');
        }

        return view('survey.admin.edit_question', compact('question'));
    }


    public function updateQuestion(Request $request, $id)
    {
        $this->checkAdmin();

        $question = SurveyQuestion::findOrFail($id);

        $request->validate([
            'question' => 'required|string|max:255',
            'type' => 'required|in:text,radio,checkbox',
        ]);

        $question->update([
            'question' => $request->question,
            'type' => $request->type
        ]);

        /**
         * HANDLE OPTIONS
         * hanya untuk radio & checkbox
         */
        if (in_array($request->type, ['radio', 'checkbox'])) {

            // hapus option lama
            $question->options()->delete();

            // simpan option baru
            if ($request->options) {
                foreach ($request->options as $opt) {
                    if (!empty(trim($opt))) {
                        SurveyOption::create([
                            'question_id' => $question->id,
                            'option_text' => trim($opt)
                        ]);
                    }
                }
            }
        } else {
            // kalau type = text, hapus semua option
            $question->options()->delete();
        }

        return redirect()
            ->route('admin.question.edit', $question->id)
            ->with('success', 'Pertanyaan berhasil diupdate');
    }

    public function deleteQuestion($id)
    {
        $this->checkAdmin();
        SurveyQuestion::findOrFail($id)->delete();
        return back();
    }

    /* =====================
        WORKER
    ===================== */
    public function form($survey_id)
    {
        if (!session('verified_worker')) {
            return redirect()->route('survey.verify');
        }

        if (session('verified_worker') === 'ADMIN_IT_HRIS') {
            abort(403);
        }

        $survey = Survey::with('questions.options')->findOrFail($survey_id);

        // ✅ CEK SUDAH SUBMIT ATAU BELUM
        $alreadySubmit = SurveySubmission::where('survey_id', $survey->id)
            ->where('employee_id', session('verified_worker'))
            ->exists();

        if ($alreadySubmit) {
            return redirect()
                ->route('survey.already')
                ->with('info', 'You have already completed this survey. Thank you.');
        }

        return view('survey.form', compact('survey'));
    }


    public function publish($id)
    {
        $this->checkAdmin();

        // matikan semua survey
        Survey::where('is_active', true)->update(['is_active' => false]);

        // aktifkan survey terpilih
        Survey::where('id', $id)->update(['is_active' => true]);

        return back()->with('success', 'Survey successfully published');
    }



    public function submit(Request $request)
    {
        $employeeId = session('verified_worker');

        // ❌ Belum login atau admin
        if (!$employeeId || $employeeId === 'ADMIN_IT_HRIS') {
            abort(403);
        }

        // ✅ Validasi input
        $validated = $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'answers' => 'required|array'
        ]);

        // ❗ Cegah submit 2x survey yang sama
        $alreadySubmit = SurveySubmission::where('survey_id', $validated['survey_id'])
            ->where('employee_id', $employeeId)
            ->exists();

        if ($alreadySubmit) {
            return back()->withErrors(['message' => 'Anda sudah mengisi survey ini.']);
        }

        $survey = Survey::findOrFail($validated['survey_id']);
        $worker = Worker::where('employee_id', $employeeId)->firstOrFail();

        // CREATE SUBMISSION (HEADER)
        $submission = SurveySubmission::create([
            'survey_id' => $validated['survey_id'],
            'employee_id' => $employeeId,
            'fullname' => $worker->fullname,
            'survey_date' => now()->format('Y-m-d'),
            'survey_time' => now()->format('H:i:s'),
        ]);

        // SAVE ANSWERS (DETAIL)
        foreach ($validated['answers'] as $question_id => $answer) {
            if (empty($answer))
                continue;

            SurveyAnswer::create([
                'submission_id' => $submission->id,
                'question_id' => $question_id,
                'answer' => is_array($answer) ? implode(', ', $answer) : $answer
            ]);
        }

        return view('survey.thankyou');
    }




    public function listResult()
    {
        $this->checkAdmin();

        $scoreMap = [
            'Excellent' => 5,
            'Very Effective' => 5,
            'Very Caring' => 5,
            'Good' => 4,
            'Effective' => 4,
            'Caring' => 4,
            'Fair' => 3,
            'Moderately Effective' => 3,
            'Moderately Caring' => 3,
            'Poor' => 2,
            'Less Effective' => 2,
            'Less Caring' => 2,
            'Very Poor' => 1,
            'Not Effective' => 1,
            'Not Caring' => 1,
        ];

        $submissions = SurveySubmission::with(['survey', 'answers'])
            ->orderBy('survey_id')
            ->get()
            ->groupBy('survey_id');

        $surveyCharts = [];

        foreach ($submissions as $surveyId => $surveySubmissions) {

            $survey = $surveySubmissions->first()->survey;

            $ok = 0;
            $notOk = 0;

            foreach ($surveySubmissions as $submission) {
                foreach ($submission->answers as $answer) {

                    if (!isset($scoreMap[$answer->answer]))
                        continue;

                    if ($scoreMap[$answer->answer] >= 4) {
                        $ok++;
                    } else {
                        $notOk++;
                    }
                }
            }

            $total = $ok + $notOk;

            $surveyCharts[$surveyId] = [
                'survey_name' => $survey->survey_name,
                'ok' => $ok,
                'not_ok' => $notOk,
                'ok_percent' => $total ? round(($ok / $total) * 100, 1) : 0,
                'not_ok_percent' => $total ? round(($notOk / $total) * 100, 1) : 0,
            ];
        }

        return view('survey.admin.list', compact('submissions', 'surveyCharts'));
    }






    public function resultDetail($id)
    {
        $this->checkAdmin();

        $submission = SurveySubmission::with([
            'survey',
            'answers.question'
        ])->findOrFail($id);

        return view('survey.admin.results', compact('submission'));
    }



}
