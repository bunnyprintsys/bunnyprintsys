<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateUpdateRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            'password' => 'confirmed',
            'profile_id' => 'nullable|exists:profiles,id',
        ];

        if ($this->input('id') && !empty($this->input('id'))) {
            return array_merge($rules, [
                'email' => 'required|unique:users,email,'.$this->input('id'),
                'phone_number' => 'required|unique:users,phone_number,'.$this->input('id'),
            ]);
        }
        return $rules;
    }
}
