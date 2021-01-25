<?php

namespace App\Http\Resources;

use App\Traits\HasMultiplierType;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShapeResource extends JsonResource
{
    use HasMultiplierType;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $multiplier = $this->getSingleMultiplierWithType($request->type);

        return [
            'id' => $this->id,
            'product' => new ProductResource($this->product),
            'shape' => new ShapeResource($this->shape),
            'multiplier' => $multiplier
        ];
    }
}
