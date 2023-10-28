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
            'mobile_phone'      => $this->mobile_phone,
            'email'             => $this->email,
            'photo_url'         => $this->photo_url !== null ? URL::to("/storage/images/") . $this->photo_url : null,
            'avatar_text_color' => $this->avatar_text_color,
            'gender'            => $this->gender,
            'about'             => $this->about,
            'date_of_birthday'  => $this->date_of_birthday,
            'status'            => $this->status,
            'created_at'        => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at'        => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
