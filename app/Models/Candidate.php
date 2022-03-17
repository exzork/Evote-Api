<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory,Uuids,SoftDeletes;

    protected $fillable = [
        'election_id','leader_id','vice_leader_id','description','image_path','image_url','votes','created_by','updated_by'
    ];

    public function leader()
    {
        return $this->belongsTo(User::class,'leader_id');
    }

    public function vice_leader()
    {
        return $this->belongsTo(User::class,'vice_leader_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Committee::class,'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Committee::class,'updated_by');
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }
}
