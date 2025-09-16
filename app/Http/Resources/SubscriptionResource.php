<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'package_id' => $this->package_id,
            'paid_amount' => $this->paid_amount,
            'screenshot' => $this->screenshot
                ? asset('storage/' . $this->screenshot)
                : null,
            'status' => $this->status,
            'expire_at' => $this->expire_at,'package'     => [
            'id'          => $this->package->id ?? null,
            'name'        => $this->package->name ?? null,
            'percent'       => $this->package->percent ?? null,
            'duration'    => $this->package->duration ?? null,
        ],
        ];
    }
}
