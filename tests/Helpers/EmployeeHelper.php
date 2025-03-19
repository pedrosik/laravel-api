<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Models\Company;
use App\Models\Employee;

class EmployeeHelper
{
    public static function create(): Employee
    {
        return Employee::factory()->create();
    }

    public static function createWithCompany(Company $company): Employee
    {
        return Employee::factory()->create([
            'company_id' => $company->id,
        ]);
    }
}
