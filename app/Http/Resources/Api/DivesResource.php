<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class DivesResource extends JsonResource
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
            "id" => (string)$this->DIV_NUM_DIVE,
            "type" => "Dive",
            "attributes" => [
                "date" => $this->DIV_DATE,
                "min_registered" => $this->DIV_MIN_REGISTERED,
                "max_registered" => $this->DIV_MAX_REGISTERED,
                "observation" => $this->DIV_OBSERVATION,
                "boat" => $this->boat,
                "site" => $this->site,
                "period" => $this->period,
                "surface_security" => $this->surfaceSecurity,
                "leader" => $this->leader,
                "pilot" => $this->pilot,
                "divers" => $this->divers,
                "groups" => $this->groups->unique("GRP_NUM_GROUPS"),
            ]
        ];
    }
}
