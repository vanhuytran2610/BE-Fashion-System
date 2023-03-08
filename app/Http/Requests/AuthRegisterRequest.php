<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthRegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:users', 
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|same:password', 
            'firstname' => 'required|min:2|max:100', 
            'lastname' => 'required|min:2|max:7', 
            'role_id' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'Error',
                'message' => 'Validations fails',
                'error' => $validator->errors()
            ], 422)
        );
    }
}
