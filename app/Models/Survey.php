<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = ['survey_name', 'is_active'];


    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public function targets()
    {
        return $this->hasMany(SurveyTarget::class);
    }

    public function submissions()
    {
        // Satu survey bisa punya banyak submission
        return $this->hasMany(SurveySubmission::class, 'survey_id', 'id');
    }

}

