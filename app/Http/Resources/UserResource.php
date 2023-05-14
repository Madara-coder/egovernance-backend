<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id"    =>  (int)$this->id,
            "name"  =>  $this->name,
            "email" =>  $this->email,
            "address"   =>  $this->address,
            "number"    =>  $this->number,
            "created_at" => $this->created_at,
        ];
    }
}
