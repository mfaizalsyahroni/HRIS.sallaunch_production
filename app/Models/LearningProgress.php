<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningProgress extends Model
{
    use HasFactory;

    protected $table = 'learning_progress';

    protected $fillable = [

        // Worker Info Snapshot
        'worker_id',
        'employee_id',
        'fullname',
        'role',

        // Module Info
        'module_id',

        // Feedback Upload
        'feedback_video',

        // Progress Data
        'status',
        'progress_percent',
    ];

    /*
    RELATIONSHIP
    Progress belongs to Worker
    */

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }



    /*
    Progress belongs to Module
    */

    public function module()
    {
        return $this->belongsTo(LearningModule::class, 'module_id');
    }

    public function certification()
    {
        return $this->hasOne(Certification::class);
    }
}
