<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this->product->toArray());
        return [
            'id' => $this->id,
            'code' => $this->code,
            'doc_no' => $this->doc_no,
            'doc_date' => $this->doc_date ? Carbon::parse($this->doc_date)->toDateString() : '',
            'delivery_method' => $this->deliveryMethod ? new DeliveryMethodResource($this->deliveryMethod) : null,
            'delivery_remarks' => $this->delivery_remarks,
            'qty' => $this->qty,
            'remarks' => $this->remarks,
            'customer' => $this->customer ? new CustomerResource($this->customer) : null,
            'product' => $this->product ? new ProductResource($this->product) : null,
            'status' => $this->status ? new StatusResource($this->status) : null,
            'uom' => $this->product->uom ? new UomResource($this->product->uom) : null,
            'address' => $this->deliveryAddress ? new AddressResource($this->deliveryAddress) : null,
        ];
    }
}
