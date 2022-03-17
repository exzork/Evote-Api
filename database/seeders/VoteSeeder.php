<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\Event;
use App\Models\Vote;
use App\Models\Voter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Voter::all()->each(function (Voter $voter) {
            if ($voter->user != null) {
                if(Arr::random([true, false])) {
                    $selection = [];
                    $event = $voter->event;
                    $event->elections()->each(function (Election $election) use (&$selection) {
                        if ($election->candidates()->count() > 0) {
                            $selection[$election->id] = Arr::random($election->candidates()->pluck('id')->toArray());
                        }
                    });
                    Vote::factory()->create([
                        'voter_id' => $voter->id,
                        'event_id' => $voter->event_id,
                        'votes'=>json_encode($selection),
                        'is_valid'=>Arr::random([true, false])
                    ]);
                }
            }
        });
    }
}
