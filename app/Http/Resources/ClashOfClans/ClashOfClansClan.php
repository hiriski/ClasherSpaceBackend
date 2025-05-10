<?php

namespace App\Http\Resources\ClashOfClans;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClashOfClansClan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'clanTag'           => $this->clanTag,
            'clanInformation'   => json_decode($this->data),
            'createdAt'         => $this->createdAt,
            'updatedAt'         => $this->updatedAt,
        ];
    }
}
