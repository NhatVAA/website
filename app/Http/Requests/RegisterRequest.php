<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'phoneNumber' => 'required',
            'birth' => 'required|date',
            'gender' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'Bạn chưa nhập vào email.',
            'email.email' => 'Email chưa nhập đúng định dạng. Ví dụ Abc@gmail.com ',
            'password.required' => 'Bạn chưa nhập vào password.',
            'name.required' => 'required',
            'phoneNumber.required' => 'required',
            'birth.required' => 'required',
            'birth.date' => 'date',
            'gender.required' => 'required',
        ];
    }
}
