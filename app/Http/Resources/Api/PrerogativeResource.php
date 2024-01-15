<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PrerogativeResource extends JsonResource
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
            "id" => (string)$this->PRE_NUM_PREROG,
            "type" => "Prerogative",
            "attributes" => [
                "level" => $this->PRE_LEVEL,
                "label" => $this->PRE_LABEL,
                "priority" => $this->PRE_PRIORITY,
            ]
        ];
    }
}
