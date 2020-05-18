<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbob\Carbon;

class TransactionResource extends JsonResource
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
            'job_id' => $this->job_id,
            'order_date' => Carbon::parse($this->order_date)->toDateString(),
            'job' => $this->job,
            'cost' => $this->cost,
            'is_artwork_provided' => $this->is_artwork_provided ? true : false,
            'is_design_required' => $this->is_design_required ? true : false,
            'invoice_id' => $this->invoice_id,
            'dispatch_date' => Carbon::parse($this->dispatch_date)->toDateString(),
            'status' => $this->status,
            'tracking_number' => $this->tracking_number,
            'customer_id' => $this->customer_id,
            'receiver_id' => $this->receiver ? $this->receiver_id : null,
            'receiver_name' => $this->receiver ? $this->receiver->name : null,
            'receiver_phone_number' => $this->receiver ? $this->receiver->phone_number : null,
            'receiver_address' => $this->receiver ? $this->receiver->address : null,
            'handler_id' => $this->handler ? $this->handler->id : null,
            'handler_name' => $this->handler ? $this->handler->user->name : null,
        ];
    }
}
