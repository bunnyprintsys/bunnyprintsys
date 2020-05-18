<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabelStickerRequest extends FormRequest
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
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'material_id' => 'required',
            'orderquantity_id' => 'required',
            'shape_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'width.required' => 'Please fill in the Width',
            'width.numeric' => 'Width must be in numbers',
            'product_material_id.required' => 'Please select a Material',
            'order_quantity.required' => 'Please fill in the Quantity',
            'product_shape_id.required' => 'Please select a Shape',
        ];
    }
}
