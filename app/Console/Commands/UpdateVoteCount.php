<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateVoteCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vote-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculating the vote count for all the candidates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $events = Event::with(['elections','votes'])->get();//->whereDate('start_date','>',Carbon::now())->whereDate('end_date','<',Carbon::now())->get();
        foreach ($events as $event) {
            $votes = $event->votes->where('is_valid',true)->pluck('votes')->toArray();
            foreach ($event->elections as $election) {
                $election->candidates->each(function ($candidate) use ($votes,$election) {
                    $candidate->votes = 0;
                    foreach ($votes as $vote) {
                        $vote = json_decode($vote,true);
                        if (array_key_exists($election->id, $vote)) {
                            $candidate->votes += $vote[$election->id] == $candidate->id ? 1 : 0;
                        }
                    }
                    $candidate->save();
                });
            }
        }
        return 0;
    }
}
