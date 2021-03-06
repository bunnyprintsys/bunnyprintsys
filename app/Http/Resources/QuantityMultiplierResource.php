<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuantityMultiplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = $request->type ? $request->type : 'customer';
        $multiplier = '';
        switch($type) {
            case 'customer':
                $multiplier = $this->customerMultipliers ? $this->customerMultipliers->first()->value : null;
                break;
            case 'agent':
                $multiplier = $this->agentMultipliers ? $this->agentMultipliers->first()->value : null;
                break;
        }

        return [
            'id' => $this->id,
            'min' => $this->min,
            'max' => $this->max,
            'multiplier' => $multiplier
        ];
    }
}
