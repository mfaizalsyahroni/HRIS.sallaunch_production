<?php

namespace App\Models;

use App\Models\Worker;
use App\Models\IdeaVote;
use App\Models\IdeaReview;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Idea extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'problem',
        'solution',
        'impact',
        'attachment',
        'demo_video',
        'status',
    ];

    //RELATIONSHIPS


    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'user_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(IdeaVote::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(IdeaReview::class);
    }

    //ACCESSORS

    // Total vote dari karyawan
    public function getVotingScoreAttribute(): int
    {
        return $this->votes()->count();
    }

    // Rata-rata skor expert
    public function getAverageExpertScoreAttribute(): float
    {
        return round(
            $this->reviews()->avg(
                DB::raw('(business_impact + feasibility + sustainability) / 3')
            ) ?? 0,
            2
        );
    }

    // HELPER ACCESSOR (Optional Enhancement)

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment
            ? asset('storage/' . $this->attachment)
            : null;
    }

    public function getDemoVideoUrlAttribute(): ?string
    {
        return $this->demo_video
            ? asset('storage/' . $this->demo_video)
            : null;
    }
}