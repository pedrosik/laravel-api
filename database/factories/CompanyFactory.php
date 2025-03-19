<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Helpers\CompanyHelper;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'      => $this->faker->company(),
            'nip'       => CompanyHelper::generateRandomNIP(),
            'address'   => $this->faker->streetAddress(),
            'city'      => $this->faker->city(),
            'post_code' => $this->faker->postcode(),
        ];
    }
}
