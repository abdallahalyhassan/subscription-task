<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [ 
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'duration'    => $this->duration,
            'status'      => $this->status ? 'active' : 'inactive',
           'expire_at'  => $this->expire_at?->toDateString(),
        ];
    }
}
