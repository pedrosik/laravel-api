<?php

namespace App\Http\Actions\Employees;

use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;

final readonly class DeleteEmployeeAction
{
    public function __construct(private EmployeeService $employeeService)
    {
    }

    /**
    * Delete an employee
     *
     * @group Employees
     *
     * @response 204
     */
    public function __invoke(Employee $employee): JsonResponse
    {
        $this->employeeService->delete($employee);

        return new JsonResponse([], 204);
    }
}