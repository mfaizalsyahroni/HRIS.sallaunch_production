<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SurveySubmission extends Model
{
    protected $table = 'survey_submissions';

    protected $fillable = [
        'survey_id',
        'survey_name',
        'employee_id',
        'fullname',
        'survey_date',
        'survey_time',
    ];

    /**
     * Relasi ke Worker via employee_id
     */
    public function worker()
    {
        return $this->belongsTo(Worker::class, 'employee_id', 'employee_id');
    }

    /**
     * Ambil fullname langsung dari Worker tanpa menimpa kolom
     */
    public function workerFullname()
    {
        return $this->worker()->select('employee_id', 'fullname');
    }

    /**
     * Relasi ke Survey
     */
    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id', 'id');
    }

    /**
     * Relasi ke SurveyAnswer (detail jawaban)
     */
    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'submission_id', 'id');
    }

    public function getSurveyDateFormattedAttribute()
    {
        return Carbon::parse($this->survey_date)->format('d-m-Y');
    }

    public function getSurveyTimeFormattedAttribute()
    {
        return Carbon::parse($this->survey_time)->format('H:i:s');
    }

    public $timestamps = true;
}
