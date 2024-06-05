<?php

namespace App\Actions\Voting;

use App\Models\Candidate;
use App\Models\Party;
use App\Models\Vote;
use App\Models\voteRecords;

class CalculateSeats
{
    public function __construct()
    {

    }

    public function calculateSeats($periodStart, $periodEnd)
    {
        $voteBump = voteRecords::where('created_at', '>=', $periodStart)
                ->where('created_at', '<=', $periodEnd)
                ->count() / 150;

        $votes = [];
        foreach (Vote::where('created_at', '>=', $periodStart)
                     ->where('created_at', '<=', $periodEnd)->get() as $vote) {
            if (!isset($votes[$vote->candidate_id])) $votes[$vote->candidate_id] = 0;
            $votes[$vote->candidate_id] += 1;
        }

        $seats = [];
        foreach ($votes as $_cid => $voteCount) {
            $candidate = Candidate::findOrFail($_cid);
            if ($voteCount > $voteBump) {
                if (!isset($seats[$candidate->parties->first()->id])) $seats[$candidate->parties->first()->id] = 0;
                $seats[$candidate->parties->first()->id] += floor($voteCount / $voteBump);
            }
        }

        return [
            'assigned_seats' => $seats,
            'remaining_seats' => (150 - array_sum($seats))
        ];
    }
}
