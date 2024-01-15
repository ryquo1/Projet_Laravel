<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => (string)$this->GRP_NUM_GROUPS,
            "type" => "Groups",
            "attributes" => [
                "expected_depth" => $this->GRP_EXPECTED_DEPTH,
                "expected_duration" => $this->GRP_EXPECTED_DURATION,
                "time_of_immersion" => $this->GRP_TIME_OF_IMMERSION,
                "time_of_emersion" => $this->GRP_TIME_OF_EMERSION,
                "reached_depth" => $this->GRP_REACHED_DEPTH,
                "diving_time" => $this->GRP_DIVING_TIME,
                "dives" => $this->dives,
                "divers" => $this->divers
            ]
        ];
    }
}
