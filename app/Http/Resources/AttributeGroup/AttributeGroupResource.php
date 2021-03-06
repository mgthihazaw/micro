<?php

namespace App\Http\Resources\AttributeGroup;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ItemType\ItemTypeResource;
use App\Http\Resources\Attribute\AttributeResource;


class AttributeGroupResource extends JsonResource
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
            'item_type' => new ItemTypeResource($this->item_type),
            'attributes' => AttributeResource::collection($this->attributes),
        ];
    }
}
