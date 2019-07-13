<?php

namespace App\Http\Requests\ItemType;

use Illuminate\Foundation\Http\FormRequest;

class ItemTypeAttributeGroupStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:attribute_groups,name',
        ];
    }
}