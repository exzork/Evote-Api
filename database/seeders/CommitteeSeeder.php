<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::withoutTrashed()->each(function (Event $event) {
            $all_user = User::all()->toArray();
            $users_committees = Arr::random($all_user, rand(1, 4));
            foreach ($users_committees as $user) {
                Committee::factory(1)->create([
                    'event_id' => $event->id,
                    'user_id' => $user['id'],
                ]);
            }
        });
    }
}
