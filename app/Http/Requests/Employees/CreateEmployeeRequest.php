<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', Rule::unique('employees', 'email')],
            'phone'      => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'first_name' => [
                'description' => 'Employee first name.',
                'example'     => 'John',
            ],
            'last_name' => [
                'description' => 'Employee last name.',
                'example'     => 'Doe',
            ],
            'email' => [
                'description' => 'Employee email.',
                'example'     => 'john.doe@example.com',
            ],
            'phone' => [
                'description' => 'Employee phone number.',
                'example'     => '+48 123 456 789',
            ],
        ];
    }
}
