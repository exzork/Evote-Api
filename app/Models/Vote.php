<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'event_id','voter_id','image_paths','image_urls','votes','is_valid'
    ];

    public function voter()
    {
        return $this->belongsTo(Voter::class, 'voter_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
