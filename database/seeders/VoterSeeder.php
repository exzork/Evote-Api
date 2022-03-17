<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Voter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::withoutTrashed()->each(function ($event) {
            while (true){
                try {
                    $event->voters()->saveMany(Voter::factory(rand(100, 200))->create([
                        'event_id' => $event->id,
                    ]));
                    break;
                }catch (\Exception $e) {
                    echo "Duplicate entry detected, retrying...\n";
                }
            }
        });
    }
}
