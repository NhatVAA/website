<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class user extends JsonResource
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
            'name' => $this-> name,
            'email' => $this -> email,
            'phoneNumber' =>$this -> phoneNumber,
            'birth' =>$this -> birth,
            'gender' =>$this -> gender,
            'updated_at' => $this->updated_at->format('d/m/Y H:i:s'),
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
        ];

    }
}
