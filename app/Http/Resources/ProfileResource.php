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
            'user_id' => $this->user ? $this->user->id : null,
            'company_name' => $this->name,
            'roc' => $this->roc,
            'address' => $this->address ? new AddressResource($this->address) : null,
            'name' => $this->user ? $this->user->name : null,
            'email' => $this->user ? $this->user->email : null,
            'phone_number' => $this->user ? $this->user->phone_number : null,
            'alt_phone_number' => $this->user ? $this->user->alt_phone_number : null,
            'country_id' => $this->country_id,
            'country_name' => $this->country ? $this->country->name : null,
            'user_id' => $this->user ? $this->user->id : null,
            'job_prefix' => $this->job_prefix,
            'invoice_prefix' => $this->invoice_prefix
        ];
    }
}
