<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Committee;
use App\Models\Election;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        Election::withoutTrashed()->with('createdBy')->each(function (Election $election) use ($users){
           $election->createdBy()->whereIn('access_level',[Committee::ACCESS_WRITE,Committee::ACCESS_ADMIN])->each(function (Committee $committee) use($election, $users){
               for ($i = 0; $i < 2; $i++) {
                   Candidate::factory()->create([
                       'election_id' => $election->id,
                       'leader_id' => $users->random()->id,
                       'vice_leader_id' => Arr::random([null,$users->random()->id]),
                       'created_by' => $committee->id,
                   ]);
               }
           });
        });
    }
}
