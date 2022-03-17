<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Election extends Model
{
    use HasFactory,Uuids, SoftDeletes;

    protected $fillable = [
        'event_id','name', 'description', 'created_by', 'updated_by'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Committee::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Committee::class, 'updated_by');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
