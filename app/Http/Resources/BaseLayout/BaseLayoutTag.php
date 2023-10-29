<?php

namespace App\Http\Resources\BaseLayout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseLayoutTag extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'isInitial'     => $this->isInitial,
        ];
    }
}
