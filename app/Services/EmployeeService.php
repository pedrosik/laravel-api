<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Collection;

final class EmployeeService
{
    public function create(array $data, Company $company): Employee
    {
        return $company->employees()->create($data);
    }

    public function listAll(): Collection
    {
        return Employee::with('company')->get();
    }

    public function update(Employee $employee, array $data): Employee
    {
        $employee->update($data);

        return $employee;
    }

    public function delete(Employee $employee): void
    {
        $employee->delete();
    }
}
