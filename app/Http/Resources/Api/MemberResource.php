<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
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
            "id" => (string)$this->MEM_NUM_MEMBER,
            "type" => "Member",
            "attributes" => [
                "licence" => $this->MEM_NUM_LICENCE,
                "name" => $this->MEM_NAME,
                "surname" => $this->MEM_SURNAME,
                "date_certification" => $this->MEM_DATE_CERTIF,
                "pricing" => $this->MEM_PRICING,
                "remaining_dives" => $this->MEM_REMAINING_DIVES,
                "subdate" => $this->MEM_SUBDATE,
                "functions" => $this->functions,
                "dives" => $this->dives,
                "groups" => $this->groups
            ]
        ];
    }
}
