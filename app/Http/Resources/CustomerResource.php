<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->user ? $this->user->name : null,
            'optionName' => $this->is_company  ? $this->company_name.' ('.$this->user->name.': '.$this->user->phoneCountry->code. $this->user->phone_number.')' : $this->user->name.' ('.$this->user->phoneCountry->code.$this->user->phone_number.')',
            'company_name' => $this->company_name,
            'roc' => $this->roc,
            'email' => $this->user ? $this->user->email : null,
            'phone_number' => $this->user ? $this->user->phone_number : null,
            'alt_phone_number' => $this->user ? $this->user->alt_phone_number : null,
            'status' => $this->user ? $this->user->status : null,
            'is_company' => $this->is_company ? 'true' : 'false',
            'user_id' => $this->user ? $this->user->id : null,
            'payment_term_id' => $this->payment_term_id,
        ];
    }
}
