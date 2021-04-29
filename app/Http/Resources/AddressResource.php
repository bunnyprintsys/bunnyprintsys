<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'block' => $this->block,
            'unit' => $this->unit,
            'building_name' => $this->building_name,
            'road_name' => $this->road_name,
            'postcode' => $this->postcode,
            'area' => $this->area,
            'state' => $this->state ? $this->state->name : '',
            'state_id' => $this->state_id,
            'country' => $this->country ? $this->country->name : '',
            'is_primary' => $this->is_primary ? true : false,
            'full_address' => $this->fullAddress,
            'is_billing' => $this->is_billing ? true : false,
            'is_delivery' => $this->is_delivery ? true : false,
            'contact' => $this->contact,
            'alt_contact' => $this->alt_contact,
            'name' => $this->name,
            'slug_address' => $this->slug_address ? $this->slug_address : null
        ];
    }
}
