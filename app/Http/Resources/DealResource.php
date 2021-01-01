<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
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
            'qty' => $this->qty,
            'price' => $this->price,
            'amount' => $this->amount,
            'description' => $this->description,
            'item' => new ProductResource($this->product),
            'material' => $this->material ? new MaterialResource($this->material) : null,
            'shape' => $this->shape ? new ShapeResource($this->shape) : null,
            'lamination' => $this->lamination ? new LaminationResource($this->lamination) : null,
            'frame' => $this->frame ? new FrameResource($this->frame) : null,
            'finishing' => $this->finishing ? new FinishingResource($this->finishing) : null
        ];
    }
}
