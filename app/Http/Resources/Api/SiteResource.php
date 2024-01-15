<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
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
            "id" => (string)$this->SIT_NUM_SITE,
            "type" => "Site",
            "attributes" => [
                "name" => $this->SIT_NAME,
                "coord" => $this->SIT_COORD,
                "depth" => $this->SIT_DEPTH,
                "description" => $this->SIT_DESCRIPTION,
            ]
        ];
    }
}
