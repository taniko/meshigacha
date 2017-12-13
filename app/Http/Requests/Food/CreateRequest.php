<?php

namespace App\Http\Requests\Food;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name'      => 'required|string',
            'calorie'   => 'required|numeric|min:0',
            'red'       => 'required|numeric|min:0',
            'green'     => 'required|numeric|min:0',
            'yellow'    => 'required|numeric|min:0',
            'price'     => 'required|integer|min:1',
            'category'  => 'string',
            'allergies'     => 'array',
            'allergies.*'   => 'string',
            'foodstuffs'    => 'array',
            'foodstuffs.*'  => 'string',
            'photos'    => 'required|min:1|array',
            'photos.*'  => 'image',
        ];
    }
}
