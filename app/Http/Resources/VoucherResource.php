<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class VoucherResource extends JsonResource
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
            'desc' => $this->desc,
            'valid_from' => Carbon::parse($this->valid_from)->toDateString(),
            'valid_to' => Carbon::parse($this->valid_to)->toDateString(),
            'is_percentage' => $this->is_percentage ? true : false,
            'is_unique_customer' => $this->is_unique_customer ? true : false,
            'is_count_limit' => $this->is_count_limit ? true : false,
            'is_active' => $this->is_active ? true : false,
            'value' => $this->value,
            'count_limit' => $this->count_limit
        ];
    }
}
