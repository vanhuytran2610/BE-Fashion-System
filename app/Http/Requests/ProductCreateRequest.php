<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:1|max:100',
            'description' => 'required|string|min:1|max:1000',
            'category_id' => 'required',
            'color_id' => 'required',
            'size' => 'required|string|min:1|max:200',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'Error',
                'message' => 'Validations fails',
                'error' => $validator->errors()
            ],422)
        );
    }
}
