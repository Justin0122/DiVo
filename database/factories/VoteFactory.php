<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Vote;
use App\Models\VotingPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VoteFactory extends Factory
{
    protected $model = Vote::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'voting_period_id' => VotingPeriod::all()->first()->id,
            'candidate_id' => Candidate::withoutTrashed()->inRandomOrder()->first()->id,
        ];
    }
}
