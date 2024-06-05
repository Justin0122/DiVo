<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\voteRecords;
use App\Models\Vote as VoteModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Vote extends Controller
{
    function index($id)
    {
        $currentCandidate = Candidate::findOrFail($id);
        $currentPartyId = $currentCandidate->parties()->first()->id;

        return view('vote',
            [
                'candidate' => $currentCandidate,
                'previousCandidate' => Candidate::whereHas('parties', function ($query) use ($currentPartyId) {
                    $query->where('parties.id', $currentPartyId);
                })
                    ->where('candidates.id', '<', $id)
                    ->orderBy('candidates.id', 'desc')
                    ->first(),
                'nextCandidate' => Candidate::whereHas('parties', function ($query) use ($currentPartyId) {
                    $query->where('parties.id', $currentPartyId);
                })
                    ->where('candidates.id', '>', $id)
                    ->first(),
            ]);
    }

    function vote($id)
    {

        $votingPeriod = \App\Models\VotingPeriod::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        if (!$votingPeriod)
            return view("notice", [
                'title' => "Voting is not open!",
                'body' => "Voting is not open at this time, please wait for the next voting period.",
                'actions' => "You should wait for the next voting period and try again."
            ]);

        if (Auth::user()->vote_records()->count() > 0)
            return view("notice", [
                'title' => "You've already voted!",
                'body' => "You can only vote once per voting period, thanks for your vote. We'll hope to see you next time!",
                'actions' => "You should wait for the next voting period and try again."
            ]);

        $voteRecords = new voteRecords();
        $voteRecords->create(Auth::user()->id);

        $vote = new VoteModel();
        $vote->create($id, Auth::user()->id);

        return view('notice', [
            'title' => "Thank you!",
            'body' => "Thanks for voting, we'll hope to see you next time!"
        ]);
    }
}
