<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nif' => 'nullable|string|max:20',
            'default_delivery_address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ];
    }
}
