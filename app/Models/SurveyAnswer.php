<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $fillable = [
        'submission_id',
        'question_id',
        'answer'
    ];

    /**
     * Jawaban milik 1 submission
     */
    public function submission()
    {
        return $this->belongsTo(SurveySubmission::class);
    }

    /**
     * Jawaban untuk 1 pertanyaan
     */
    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }
}
