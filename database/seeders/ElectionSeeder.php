<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\Election;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::withoutTrashed()->each(function (Event $event) {
            $all_user = User::with('committees')->whereRelation('committees','event_id','=',$event->id)->whereHas('committees',function ($q){
                $q->whereIn('access_level',[Committee::ACCESS_WRITE,Committee::ACCESS_ADMIN]);
            })->get();

            foreach ($all_user as $user) {
                for ($i = 0; $i < rand(1, 3); $i++) {
                    Election::factory()->create([
                        'event_id' => $event->id,
                        'created_by' => $user->committees->first()->id,
                    ]);
                }
            }
        });
    }
}
