<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'leader_id' => $this->leader_id,
            'leader_name' => $this->leader->name,
            'vice_leader_id' => $this->vice_leader_id,
            'vice_leader_name' => $this->vice_leader->name ?? null,
            'image_url' => $this->image_url,
            'description' => $this->description,
            'votes' => $this->votes,
        ];
        if($request->user()->committees()->where('event_id', $this->election->event_id)->exists()){
            $data['created_by'] = CommitteeResource::make($this->createdBy);
            $data['updated_by'] = CommitteeResource::make($this->updatedBy);
        }
        return $data;
    }
}
