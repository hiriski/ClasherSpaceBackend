<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class User extends JsonResource
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
            'email'             => $this->email,
            'photoUrl'          => $this->photoUrl,
            'avatarTextColor'   => $this->avatarTextColor,
            'gender'            => $this->gender,
            'about'             => $this->about,
            'dateOfBirthday'    => $this->dateOfBirthday,
            'status'            => $this->status,
            'createdAt'         => $this->createdAt,
            'updatedAt'         => $this->updatedAt,
            'authProvider'      => new AuthProvider($this->whenLoaded('authProvider')),
        ];
    }
}
