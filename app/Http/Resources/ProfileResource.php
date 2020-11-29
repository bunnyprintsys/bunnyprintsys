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
            'user' => $this->user,
            'user_id' => $this->user ? $this->user->id : null,
            'company_name' => $this->name,
            'roc' => $this->roc,
            'address' => $this->address ? new AddressResource($this->address) : null,
            'full_address' => $this->address ? $this->address->getFullAdress() : null,
            'unit' => $this->address ? $this->address->unit : null,
            'block' => $this->address ? $this->address->block : null,
            'building_name' => $this->address ? $this->address->building_name : null,
            'road_name' => $this->address ? $this->address->road_name : null,
            'postcode' => $this->address ? $this->address->postcode : null,
            'state_id' => $this->address ? $this->address->state->id : null,
            'country_id' => $this->address ? $this->address->country->id : null,
            'country_name' => $this->country ? $this->country->name : null,
            'job_prefix' => $this->job_prefix,
            'invoice_prefix' => $this->invoice_prefix,
            'bank' => $this->bankBinding ? new IdNameResource($this->bankBinding->bank) : null,
            'bank_account_holder' => $this->bankBinding ? $this->bankBinding->bank_account_holder : null,
            'bank_account_number' => $this->bankBinding ? $this->bankBinding->bank_account_number : null,
        ];
    }
}
