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
        $full_address = '';
        if($this->unit) {
            $full_address .= $this->unit.', ';
        }
        if($this->block) {
            $full_address .= $this->block.', ';
        }
        if($this->building_name) {
            $full_address .= $this->building_name.', ';
        }
        if($this->road_name) {
            $full_address .= $this->road_name.', ';
        }
        if($this->postcode) {
            $full_address .= $this->postcode.' ';
        }
        if($this->area) {
            $full_address .= $this->area.', ';
        }
        if($this->state) {
            $full_address .= $this->state->name.', ';
        }
        if($this->country) {
            $full_address .= $this->country->name.'.';
        }


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
            'full_address' => $full_address
        ];
    }
}
