<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuggestionFeedback extends Model
{
    protected $table = 'suggestion_feedbacks';
    protected $fillable = [
        'suggestion_id',
        'admin_employee_id',
        'feedback'
    ];

    public function suggestion()
    {
        return $this->belongsTo(Suggestion::class);
    }

    public function admin()
    {
        return $this->belongsTo(Worker::class, 'admin_employee_id', 'employee_id');
    }
}