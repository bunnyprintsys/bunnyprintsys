<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankBindingResource extends JsonResource
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
            'bank' => new IdNameResource($this->bank),
            'bank_account_holder' => $this->bank_account_holder,
            'bank_account_number' => $this->bank_account_number,
        ];
    }
}
