<?php

namespace App\Services;

use App\Models\Idea;
use Illuminate\Support\Collection;

class FinalScoreService
{
    /* =========================================================
       CONFIG WEIGHT (BISA DIPINDAH KE CONFIG FILE NANTI)
    ========================================================= */

    protected float $votingWeight = 0.40;   // 40%
    protected float $expertWeight = 0.60;   // 60%

    // Sub-weight dari 60% expert
    protected float $businessImpactWeight = 25;
    protected float $feasibilityWeight    = 20;
    protected float $sustainabilityWeight = 15;

    /* =========================================================
       FINAL SCORE (0–100)
    ========================================================= */

    public function calculate(Idea $idea): float
    {
        $votingScore = $this->employeeVotingScore($idea); // 0–100
        $expertScore = $this->expertReviewScore($idea);   // 0–100

        $finalScore = ($votingScore * $this->votingWeight)
                    + ($expertScore * $this->expertWeight);

        return round($finalScore, 2);
    }

    /* =========================================================
       VOTING SCORE (0–100)
       Normalisasi terhadap vote tertinggi
    ========================================================= */

    protected function employeeVotingScore(Idea $idea): float
    {
        $ideas = Idea::withCount('votes')->get();

        $maxVotes = $ideas->max('votes_count');

        if (!$maxVotes || $maxVotes == 0) {
            return 0;
        }

        $ideaVotes = $idea->votes()->count();

        return ($ideaVotes / $maxVotes) * 100;
    }

    /* =========================================================
       EXPERT REVIEW SCORE (0–100)
       Business Impact (25%)
       Feasibility (20%)
       Sustainability (15%)
    ========================================================= */

    protected function expertReviewScore(Idea $idea): float
    {
        $reviews = $idea->reviews;

        if ($reviews->isEmpty()) {
            return 0;
        }

        $avgBusinessImpact = $reviews->avg('business_impact'); // 1–5
        $avgFeasibility    = $reviews->avg('feasibility');     // 1–5
        $avgSustainability = $reviews->avg('sustainability');  // 1–5

        return (
            ($avgBusinessImpact / 5) * $this->businessImpactWeight +
            ($avgFeasibility / 5)    * $this->feasibilityWeight +
            ($avgSustainability / 5) * $this->sustainabilityWeight
        );
    }

    /* =========================================================
       RANKING ENGINE
    ========================================================= */

    public function getRanking(): Collection
    {
        $ideas = Idea::where('status', 'reviewed')
            ->with(['reviews', 'votes'])
            ->get();

        $ranking = $ideas->map(function ($idea) {
            return [
                'idea'        => $idea,
                'score'       => $this->calculate($idea),
                'vote_count'  => $idea->votes->count()
            ];
        });

        // Sort:
        // 1. Score tertinggi
        // 2. Kalau sama → vote lebih tinggi menang
        return $ranking
            ->sortByDesc(function ($item) {
                return [$item['score'], $item['vote_count']];
            })
            ->values();
    }

    /* =========================================================
       WINNER
    ========================================================= */

    public function getWinner(): ?array
    {
        return $this->getRanking()->first();
    }

    public function getTop(int $limit = 3): Collection
    {
        return $this->getRanking()->take($limit);
    }

    /* =========================================================
       AUTO ASSIGN WINNER STATUS (OPTIONAL)
    ========================================================= */

    public function assignWinnerStatus(): void
    {
        $winner = $this->getWinner();

        if (!$winner) {
            return;
        }

        // Reset semua dulu
        Idea::where('status', 'reviewed')
            ->update(['status' => 'reviewed']);

        // Set winner
        $winner['idea']->update([
            'status' => 'winner'
        ]);
    }
}