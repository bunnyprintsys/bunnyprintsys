<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
            'order_date' => Carbon::parse($this->order_date)->toDateString(),
            'job' => $this->job,
            'cost' => $this->cost,
            'is_artwork_provided' => [
                'id' => $this->is_artwork_provided === 1 ? 1 : 0,
                'name' => $this->is_artwork_provided === 1 ? 'Yes' : 'No'
            ],
            'is_design_required' => [
                'id' => $this->is_design_required === 1 ? 1 : 0,
                'name' => $this->is_design_required === 1 ? 'Yes' : 'No'
            ],
            'designed_by' => [
                'id' => $this->designer ? $this->designer->id : null,
                'name' => $this->designer ? $this->designer->name : null,
            ],
            'delivery_method' => [
                'id' => $this->deliveryMethod ? $this->deliveryMethod->id : null,
                'name' => $this->deliveryMethod ? $this->deliveryMethod->name : null
            ],
            'invoice_id' => $this->invoice_id,
            'dispatch_date' => Carbon::parse($this->dispatch_date)->toDateString(),
            'status' => $this->status->name,
            'tracking_number' => $this->tracking_number,
            'customer' => new CustomerResource($this->customer),
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer->user->name,
            // 'full_address' => $this->address->getFullAdress(),
            'delivery_address' => new AddressResource($this->deliveryAddress),
            'billing_address' => new AddressResource($this->billingAddress),
            'addresses' => AddressResource::collection($this->customer->addresses),
            'phone_number' => $this->customer->user->phone_number,
            'phone_number_country_code' => new CountryResource($this->customer->user->phoneCountry),
            'alt_phone_number' => $this->customer->user->alt_phone_number,
            'alt_phone_number_country_code' => new CountryResource($this->customer->user->altPhoneCountry),
            'grandtotal' => $this->grandtotal,
            'creator' => $this->creator ? $this->creator->name : null,
            'sales_channel' => [
                'id' => $this->salesChannel ? $this->salesChannel->id : null,
                'name' => $this->salesChannel ? $this->salesChannel->name : null,
                'desc' => $this->salesChannel ? $this->salesChannel->desc : null
            ],
            'status' => [
                'id' => $this->status->id,
                'name' => $this->status->name
            ],
            'items' => DealResource::collection($this->deals),
            'remarks' => $this->remarks
        ];
    }
}
