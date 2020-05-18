<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'last_login_at' => $this->last_login_at,
            // 'profile' => new ProfileResource($this->profile),
            'roles' => IdNameResource::collection($this->roles),
            'status' => [
                'id' => $this->status,
                'name' => $this->isActive() ? 'Active' : 'Inactive'
            ],
        ];
    }
}
