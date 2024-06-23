<?php

namespace App\Http\Resources\ClashOfClans;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClashOfClansPlayer extends JsonResource
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
            'playerTag'         => $this->playerTag,
            'playerInformation' => json_decode($this->data),
            'createdAt'         => $this->createdAt,
            'updatedAt'         => $this->updatedAt,
        ];
    }
}
