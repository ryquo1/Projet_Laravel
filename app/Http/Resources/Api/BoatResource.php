<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class BoatResource extends JsonResource
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
            "id" => (string)$this->BOA_NUM_BOAT,
            "type" => "Boat",
            "attributes" => [
                "name" => $this->BOA_NAME,
                "capacity" => $this->BOA_CAPACITY,
            ]
        ];
    }
}
