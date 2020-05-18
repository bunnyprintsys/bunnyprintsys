<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AdminResource extends JsonResource
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
            'join_date' => Carbon::parse($this->join_date)->toDateString(),
            'leave_date' => Carbon::parse($this->leave_date)->toDateString(),
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone_number' => $this->user->phone_number,
            'alt_phone_number' => $this->user->alt_phone_number,
            'status' => $this->user->status,
            'user_id' => $this->user->id
        ];
    }
}
