<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'name'  => 'string',
            'where' => 'string',
            'lat'   => 'numeric|required_with_all:lng',
            'lng'   => 'numeric|required_with_all:lat',
            'distance' => 'numeric|required_with_all:lat,lng',
        ];
    }
}
