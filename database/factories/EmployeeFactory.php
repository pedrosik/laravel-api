<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Helpers\CompanyHelper;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        $company = Company::query()->first() ?? CompanyHelper::create();

        return [
            'email'      => $this->faker->safeEmail(),
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'phone'      => $this->faker->phoneNumber(),
            'company_id' => $company->id,
        ];
    }
}
