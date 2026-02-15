<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = [
        'employee_id',
        'category',
        'title',
        'description',
        'status',
        'attachment_path',
        'attachment_type'
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'employee_id', 'employee_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(SuggestionFeedback::class, 'suggestion_id');
    }

}

