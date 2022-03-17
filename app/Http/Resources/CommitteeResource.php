<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

class CommitteeResource extends JsonResource
{
    protected bool $withEvent = false;
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {

        $data = [
          'id' => $this->id,
          'user_name' => $this->user->name,
          'position' => $this->position,
          'access_level' => (int)$this->access_level,
        ];

        if ($this->withEvent) {
            unset($data['event_id']);
            $data['event'] = new EventResource($this->event);
        }

        return $data;
    }

    public function withEvent(): static
    {
        $this->withEvent = true;
        return $this;
    }
}
