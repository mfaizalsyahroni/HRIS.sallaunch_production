<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyOption;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // list questions for a survey
    public function index($survey_id)
    {
        $survey = Survey::with('questions.options')->findOrFail($survey_id);
        $questions = $survey->questions;
        return view('survey.admin.questions', compact('survey', 'questions'));
    }

    // show add question form
    public function create($survey_id)
    {
        $survey = Survey::findOrFail($survey_id);
        return view('survey.admin.add_question', compact('survey'));
    }

    // store question
    public function store(Request $request, $survey_id)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:text,radio,checkbox',
            'options' => 'nullable|string' // comma separated
        ]);

        $q = SurveyQuestion::create([
            'survey_id' => $survey_id,
            'question' => $request->question,
            'type' => $request->type
        ]);

        if (in_array($request->type, ['radio', 'checkbox']) && $request->options) {
            $opts = array_filter(array_map('trim', explode(',', $request->options)));
            foreach ($opts as $opt) {
                SurveyOption::create([
                    'question_id' => $q->id,
                    'option_text' => $opt
                ]);
            }
        }

        return redirect()->route('survey.questions', $survey_id)->with('success', 'Pertanyaan ditambahkan.');
    }

    public function edit($id)
    {
        $question = SurveyQuestion::with('options')->findOrFail($id);
        return view('survey.admin.edit_question', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:text,radio,checkbox',
            'options' => 'nullable|string'
        ]);

        $q = SurveyQuestion::findOrFail($id);
        $q->update([
            'question' => $request->question,
            'type' => $request->type
        ]);

        // refresh options
        if (in_array($request->type, ['radio', 'checkbox'])) {
            // delete old
            $q->options()->delete();
            if ($request->options) {
                $opts = array_filter(array_map('trim', explode(',', $request->options)));
                foreach ($opts as $opt) {
                    SurveyOption::create([
                        'question_id' => $q->id,
                        'option_text' => $opt
                    ]);
                }
            }
        } else {
            // remove options if changed to text
            $q->options()->delete();
        }

        return redirect()->route('survey.questions', $q->survey_id)->with('success', 'Pertanyaan updated.');
    }

    public function destroy($id)
    {
        $q = SurveyQuestion::findOrFail($id);
        $survey_id = $q->survey_id;
        $q->options()->delete();
        $q->delete();
        return redirect()->route('survey.questions', $survey_id)->with('success', 'Pertanyaan dihapus.');
    }
}
