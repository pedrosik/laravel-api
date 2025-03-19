<?php

namespace App\Http\Actions\Companies;

use App\Http\Requests\Employees\CreateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Company;
use App\Services\EmployeeService;

final readonly class CreateEmployeeAction
{
    public function __construct(private EmployeeService $employeeService)
    {
    }

    /**
     * Create a new employee
     *
     * @group Company
     *
     * @response 200 storage/responses/employees/employee.json
     */
    public function __invoke(CreateEmployeeRequest $request, Company $company): EmployeeResource
    {
        $employee = $this->employeeService->create($request->validated(), $company);

        return new EmployeeResource($employee->loadMissing('company'));
    }
}