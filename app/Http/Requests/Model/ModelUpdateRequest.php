<?php

namespace App\Http\Requests\Model;

use Illuminate\Foundation\Http\FormRequest;

class ModelUpdateRequest extends FormRequest
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
            'name' => 'min:1|unique:models,name,'.$this->model->id,
            'brand_id' => 'required|integer|exists:brands,id'
        ];
    }
}
