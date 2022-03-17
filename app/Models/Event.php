<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Pivot
{
    use HasFactory,SoftDeletes, Uuids;
    protected $table = 'events';
    protected $fillable = [
        'user_id',
        'name',
        'image_path',
        'image_url',
        'description',
        'start_date',
        'end_date',
        'is_active'
    ];

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function committees()
    {
        return $this->hasMany(Committee::class, 'event_id');
    }

    public function elections(){
        return $this->hasMany(Election::class, 'event_id');
    }

    public function voters(){
        return $this->hasMany(Voter::class, 'event_id');
    }

    public function votes(){
        return $this->hasMany(Vote::class, 'event_id');
    }
}
