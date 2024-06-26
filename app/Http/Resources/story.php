<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class story extends JsonResource
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
            'privacy' => $this -> privacy,
            'id_User' => $this ->id_User,
            'updated_at' => $this->updated_at->format('d/m/Y H:i:s'),   
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            // 'updated_at' => $this->updated_at,
            // 'created_at' => $this->created_at,
            'photos' => $this->photos->toArray(),
            'videos' => $this->videos->toArray(),
            'likestorys' => $this->likestorys->toArray(),
        ];

    }
}
