<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\voteRecords;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class voteRecordsFactory extends Factory
{
    protected $model = voteRecords::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_id' => \App\Models\User::withoutTrashed()->inRandomOrder()->first()->id
        ];
    }
}
