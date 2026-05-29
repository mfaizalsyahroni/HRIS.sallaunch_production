<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningModule extends Model
{
    use HasFactory;

    protected $table = 'learning_modules';


    const CERT_COMPETENCY = 'Certificate of Competency';
    const CERT_PROFICIENCY = 'Certificate of Proficiency';
    const CERT_MASTERY = 'Certificate of Mastery';


    const CERTIFICATE_TITLES = [
        'competency' => self::CERT_COMPETENCY,
        'proficiency' => self::CERT_PROFICIENCY,
        'mastery' => self::CERT_MASTERY,
    ];

    protected $fillable = [
        'module_name',
        'category',
        'youtube_id',
        'duration',
        'description',
        'certificate_title',
    ];


    //This is a Laravel accessor — 
    // automatically called when you access $module->certificate_title_label.
    public function getCertificateTitleLabelAttribute(): string
    {
        return self::CERTIFICATE_TITLES[$this->certificate_title]
            ?? self::CERT_COMPETENCY;
    }

    /*
    1 modul bisa punya banyak progress staff
    */
    public function progress()
    {
        return $this->hasMany(LearningProgress::class, 'module_id');
    }

    //LearningModule → has many Certifications
    //Certification → belongs to one LearningModule
    public function certification()
    {
        return $this->hasMany(Certification::class, 'module_id');
    }

    public function worker()
    {
        return $this->belongsToMany('learning_progress', 'module_id', 'employee_id')
                    ->withPivot(['status', 'progres_recent', 'completed_at'])
                    ->withTimestamps(); 
    }

    public  function latestFeedback() 
    {   
        //“Taking exactly ONE latest progress record that contains feedback_video.”
        return $this->hasone(LearningProgress::class, 'module_id')
                    ->whereNotNull('feedback_video') //Must have a file (feedback video).
                    ->lastOfMany(); // etrieve 1 latest progress record with the most recent feedback video
    }


}
