<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ElectionResource extends JsonResource
{
    protected $withEvent = false;
    protected $withCommittee = false;
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
            'event_id' => $this->event_id,
            'name' => $this->name,
            'description' => $this->description,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];

        if ($this->withEvent) {
            $data['event'] = new EventResource($this->event);
        }

        if ($this->withCommittee) {
            $data['created_by'] = CommitteeResource::make($this->createdBy)->withUser();
            $data['updated_by'] = CommitteeResource::make($this->updatedBy)->withUser();
        }
        return $data;
    }
}
