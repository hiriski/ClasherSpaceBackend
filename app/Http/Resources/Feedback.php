<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Feedback extends JsonResource
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
            'type'              => $this->type,
            'email'             => $this->email,
            'body'              => $this->body,
            'createdAt'         => $this->createdAt ? $this->createdAt->toDateTimeString() : null,
            'updatedAt'         => $this->updatedAt ? $this->updatedAt->toDateTimeString() : null,
        ];
    }
}
