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
            'calorie'   => 'required|numeric',
            'red'       => 'required|numeric',
            'green'     => 'required|numeric',
            'yellow'    => 'required|numeric',
            'price'     => 'required|integer',
            'category'  => 'string',
            'allergies.*'   => 'string',
            'foodstuffs.*'  => 'string',
        ];
    }
}
