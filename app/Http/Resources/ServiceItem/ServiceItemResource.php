<?php

namespace App\Http\Resources\ServiceItem;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ServiceItem\ItemResource;

class ServiceItemResource extends JsonResource
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
            'customer_name' => $this->customer_id == null ? $this->customer_name : $this->customer->person_business->name,
            'customer_phone' => $this->customer_id == null ? $this->customer_phone : $this->customer->person_business->phone,
            'customer_address' => $this->customer_id == null ? $this->customer_address : $this->customer->person_business->address_line,
            'staff' => $this->staff->person_business->name,
            'staff_id' => $this->staff->id,
            'service_engineer' => $this->service_engineer === null ? '----' : $this->service_engineer->person_business->name,
            'customer_id' => $this->customer_id,
            'received_description' => $this->received_description,
            'received_remark' => $this->received_remark,
            'pending' => $this->pending,
            'service_description' => $this->service_description,
            'check_results' => $this->check_results,
            'created_at' =>$this->created_at,
            'items' => ItemResource::collection($this->items),
            'invoice_id' => $this->invoice->id
            //'items' => $this->items
        ];
    }
}
