<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
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
            'company_name' => $this->company_name,
            'roc' => $this->roc,
            'email' => $this->user ? $this->user->email : null,
            'phone_number' => $this->user ? $this->user->phone_number : null,
            'alt_phone_number' => $this->user ? $this->user->alt_phone_number : null,
            'status' => $this->user ? $this->user->status : null,
            'is_company' => $this->is_company,
            'credit' => number_format($this->credit/ 100, 2),
            'user_id' => $this->user ? $this->user->id : null
        ];
    }
}