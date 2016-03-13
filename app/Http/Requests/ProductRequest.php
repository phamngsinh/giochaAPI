<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductRequest extends Request
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
            'name'=> 'required|min:6',
            'price'=> 'required',
            'creator'=> 'required',
        ];
    }
    public function messages()
    {
       return [
           'name.required' => 'Product\'s name is required',
           'name.min' => 'Minimum of  name is 6 characters',
           'price.required' => 'Product\'s price is required',
           'creator.required' => 'Creator  is required',
       ];
    }
}
