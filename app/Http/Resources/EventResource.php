<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    protected bool $withUser = false;
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
            'name' => $this->name,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => (bool)$this->is_active,
        ];
        if ($request->user()->voters()->where('event_id', $this->id)->exists()) {
            $data['voted'] = $request->user()->votes()->where('votes.event_id', $this->id)->exists();
        }
        if ($this->withUser) {
            $data['user'] = new UserResource($this->user);
        }
        return $data;
    }

    public function withUser()
    {
        $this->withUser = true;
        return $this;
    }
}
