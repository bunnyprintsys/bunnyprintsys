<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'product_code' => $this->product_code,
            'is_material' => $this->is_material,
            'is_shape' => $this->is_shape,
            'is_lamination' => $this->is_lamination,
            'is_frame' => $this->is_frame,
            'is_finishing' => $this->is_finishing,
        ];
    }
}
