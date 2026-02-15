<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $table = 'certifications';

    protected $fillable = [

        // Relasi inti
        'learning_progress_id',
        'worker_id',
        'employee_id',
        'module_id',

        // Keputusan
        'score',        // A / B / C
        'status',       // passed / failed
        'notes',        // catatan penilai (opsional)

        // Auditor
        'reviewed_by',  // worker_id MT / admin
    ];

    /* 
        RELATIONSHIP
     */

    // Sertifikasi ini milik 1 aktivitas belajar
    public function progress()
    {
        return $this->belongsTo(LearningProgress::class, 'learning_progress_id');
    }

    // Sertifikasi ini milik 1 worker
    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    // Sertifikasi untuk 1 modul
    public function module()
    {
        return $this->belongsTo(LearningModule::class, 'module_id');
    }

    // Penilai (MT / Admin)
    public function reviewer()
    {
        return $this->belongsTo(Worker::class, 'reviewed_by');
    }
}