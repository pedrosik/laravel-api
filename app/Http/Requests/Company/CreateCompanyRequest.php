<?php

namespace App\Http\Requests\Company;

use App\Rules\NipRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'nip'       => ['required', 'string', new NipRule()],
            'address'   => ['required', 'string', 'max:255'],
            'city'      => ['required', 'string', 'max:255'],
            'post_code' => ['required', 'string', 'max:6'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Company name.',
                'example'     => 'NFD',
            ],
            'nip' => [
                'description' => 'Company NIP.',
                'example'     => '1234567890',
            ],
            'address' => [
                'description' => 'Company address.',
                'example'     => '123 Main St',
            ],
            'city' => [
                'description' => 'Company city.',
                'example'     => 'Warsaw',
            ],
            'post_code' => [
                'description' => 'Company post code.',
                'example'     => '00-000',
            ],
        ];
    }
}
