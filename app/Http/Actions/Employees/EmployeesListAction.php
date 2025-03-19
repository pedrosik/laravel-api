<?php

namespace App\Http\Actions\Employees;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final readonly class EmployeesListAction
{
    /**
     * List all employees
     *
     * @group Employees
     *
     * @response 200 storage/responses/employees/employees.json
     */
    public function __invoke(): AnonymousResourceCollection
    {
        return EmployeeResource::collection(Employee::with('company')->get());
    }
}