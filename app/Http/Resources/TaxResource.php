<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\NumberFormatting;

class TaxResource extends JsonResource
{
    use NumberFormatting;
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
            'rate' => $this->removeTraillingZero($this->rate),
            'desc' => $this->desc,
        ];
    }
}
