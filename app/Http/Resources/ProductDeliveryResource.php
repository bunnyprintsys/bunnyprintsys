<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDeliveryResource extends JsonResource
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
            'product' => new ProductResource($this->product),
            'delivery' => new DeliveryResource($this->delivery),
            'multiplier' => $multiplier
        ];
    }
}
