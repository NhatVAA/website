<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public function toArray(Request $request): array
    // {
    //     return parent::toArray($request);
    // }
    public function toArray(Request $request): array
    {
        return [
            'id' => $this -> id,
            'content' => $this -> content,
            'privacy' => $this -> privacy,
            'id_User' => $this ->id_User,
            'updated_at' => $this->updated_at,   
            'created_at' => $this->created_at,
            'photos' => $this->photos->toArray(),
            'videos' => $this->videos->toArray(),
            'user' => $this->user->toArray(),
            'comments' => array_reverse($this->comments->toArray()),
            'likes' => array_reverse($this->likes->toArray()),
        ];

    }
}
