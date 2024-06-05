<?php

namespace Database\Factories;

use App\Models\VotingPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class VotingPeriodFactory extends Factory
{
    protected $model = VotingPeriod::class;

    public function definition(): array
    {
        return [
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addWeeks(4)
        ];
    }
}
