<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PeriodResource extends JsonResource
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
            "id" => (string)$this->PER_NUM_PERIOD,
            "type" => "Period",
            "attributes" => [
                "label" => $this->PER_LABEL,
                "start_time" => $this->PER_START_TIME,
                "end_time" => $this->PER_END_TIME,
            ]
        ];
    }
}
