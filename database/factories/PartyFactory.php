<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PartyFactory extends Factory
{
    protected $model = Party::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'abbreviation' => $this->faker->word(),
            'bio' => $this->faker->word(),
            'logo' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
