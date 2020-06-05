<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'roc' => $this->roc,
            'address' => $this->address,
            'attn_name' => $this->user ? $this->user->name : null,
            'email' => $this->user ? $this->user->email : null,
            'attn_phone_number' => $this->user ? $this->user->phone_number : null,
            'country_id' => $this->country_id,
            'user_id' => $this->user ? $this->user->id : null
        ];
    }
}
