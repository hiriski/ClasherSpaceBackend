<?php

namespace App\Http\Resources\BaseLayout;

use App\Http\Resources\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseLayout extends JsonResource
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
            'name'              => $this->name,
            'link'              => $this->link,
            'description'       => $this->description,
            'userId'            => $this->userId,
            'townHallLevel'     => $this->townHallLevel,
            'builderHallLevel'  => $this->builderHallLevel,
            'baseType'          => $this->baseType,
            'imageUrls'         => $this->imageUrls !== null ? explode(",", $this->imageUrls) : null,
            'views'             => $this->views,
            'likedCount'        => $this->likedCount,
            'markedAsWarBase'   => $this->markedAsWarBase,
            'categories'        => new BaseLayoutCategoryCollection($this->whenLoaded('categories')),
            'tags'              => new BaseLayoutTagCollection($this->whenLoaded('tags')),
            'user'              => new User($this->whenLoaded('user')),
            'createdAt'         => $this->createdAt ? $this->createdAt->toDateTimeString() : null,
            'updatedAt'         => $this->updatedAt ? $this->updatedAt->toDateTimeString() : null,
        ];
    }
}
