<?php

namespace App\Http\Actions\Employees;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;

final readonly class ShowEmployeeAction
{
    /**
     * Show an employee
     *
     * @group Employees
     *
     * @response 200 storage/responses/employees/employee.json
     */
    public function __invoke(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee->loadMissing('company'));
    }
}