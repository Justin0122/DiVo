<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Livewire\Party;
use App\Models\voteRecords;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $audit = Permission::create(['name' => 'seeAudit']);
        $crudParty = Permission::create(['name' => 'crudParty']);
        $admin = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $admin->givePermissionTo($audit);
        $admin->givePermissionTo($crudParty);

        $vote = Permission::create(['name' => 'vote']);
        $user = \Spatie\Permission\Models\Role::create(['name' => 'user']);
        $user->givePermissionTo($vote);


        $this->command->info("Seeding admin...");
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'address' => 'Admin Address',
            'city' => 'Admin City',
            'province' => 'Admin Province',
            'zip' => 'Admin Zip',
            'bsn' => '123456789',
            'password' => bcrypt('password'),
            'two_factor_secret' => '1',
        ])->assignRole($admin);

        $this->command->info("Seeding users...");
        \App\Models\User::factory(10)->create();

        $this->command->info("Seeding parties...");
        \App\Models\Party::factory(10)
            ->has(\App\Models\Candidate::factory()->count(5), 'candidates')
            ->create();

        \App\Models\VotingPeriod::factory(1)->create();

        $seedVotesAmount = env('AMOUNT_SEEDED_VOTES', 150);

        if ($seedVotesAmount > 0) {
            $this->command->info("Seeding votes...");
            $this->command->warn("Creating records and estimating time for " . floor($seedVotesAmount / 10) . " votes...");
            $t1 = floor(microtime(true) * 1000);
            \App\Models\Vote::factory(floor($seedVotesAmount / 10))->create();
            $t2 = floor(microtime(true) * 1000);
            $seedVotesAmount -= floor($seedVotesAmount / 10);

            $eta = (($t2 - $t1) * 10) * 2;
            $this->command->warn("Seeding $seedVotesAmount votes... (this can take some time; ~$eta ms)");

            \App\Models\Vote::factory($seedVotesAmount)->create();
            \App\Models\voteRecords::factory($seedVotesAmount)->create();
        } else {
            $this->command->warn("Not seeding votes...");
        }
    }
}
