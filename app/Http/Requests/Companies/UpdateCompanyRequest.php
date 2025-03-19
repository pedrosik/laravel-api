<?php

namespace App\Http\Requests\Companies;

use App\Models\Company;
use App\Rules\NipRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['sometimes', 'string', 'max:255'],
            'nip'       => ['sometimes', 'string', new NipRule(), Rule::unique(Company::class)->ignore($this->company->id)],
            'address'   => ['sometimes', 'string', 'max:255'],
            'city'      => ['sometimes', 'string', 'max:255'],
            'post_code' => ['sometimes', 'string', 'max:6'],
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
