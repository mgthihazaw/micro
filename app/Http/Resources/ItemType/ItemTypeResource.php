<?php

namespace App\Http\Resources\ItemType;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Attribute\AttributeCollection;


class ItemTypeResource extends JsonResource
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
            'attributes' => new AttributeCollection($this->attributes),
            'links' => [
                'self' => route('itemtypes.show', $this->id)
            ]
        ];
    }
}