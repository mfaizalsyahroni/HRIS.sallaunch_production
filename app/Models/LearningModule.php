<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningModule extends Model
{
    use HasFactory;

    protected $table = 'learning_modules';

    protected $fillable = [
        'module_name',
        'category',
        'youtube_id',
        'duration',
        'description',
    ];

    /*
    1 modul bisa punya banyak progress staff
    */
    public function progress()
    {
        return $this->hasMany(LearningProgress::class, 'module_id');
    }


    public function certification()
    {
        return $this->belongsTo(Worker::class, 'employee_id');
    }



}
