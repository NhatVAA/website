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
            'idUser' => $this ->idUser,
            'updated_at' => $this->updated_at->format('d/m/Y'),
            'created_at' => $this->created_at->format('d/m/Y'),
        ];

    }
}
