<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function voting_period(): BelongsTo
    {
        return $this->belongsTo(VotingPeriod::class);
    }

    public function create($candidateId): void
    {
        $votingPeriod = VotingPeriod::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();
        $this->candidate()->associate(Candidate::findOrFail($candidateId));
        $this->voting_period()->associate($votingPeriod);
        $this->save();
    }

}
