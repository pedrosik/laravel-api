<?php

namespace App\Http\Actions\Employees;

use App\Http\Requests\Employees\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;

final readonly class UpdateEmployeeAction
{
    public function __construct(private EmployeeService $employeeService)
    {
    }

    /**
     * Update an employee
     *
     * @group Employees
     *
     * @response 200 storage/responses/employees/employee.json
     */
    public function __invoke(UpdateEmployeeRequest $request, Employee $employee): EmployeeResource
    {
        $employee = $this->employeeService->update($employee, $request->validated());

        return new EmployeeResource($employee->loadMissing('company'));
    }
}