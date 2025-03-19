<?php

declare(strict_types=1);

namespace Tests\Feature\Employees;

use Tests\Feature\ApiTest;
use Tests\Helpers\CompanyHelper;
use Symfony\Component\HttpFoundation\Response;

class DeleteEmployeeActionTest extends ApiTest
{
    public function test_it_deletes_employee_successfully(): void
    {
        $company = CompanyHelper::createWithEmployees(1);
        $employee = $company->employees->first();

        $this->deld(
            $this->getUser(),
            "/api/employees/{$employee->id}",
            Response::HTTP_NO_CONTENT
        );

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }

    public function test_it_returns_error_when_employee_not_found(): void
    {
        $this->deld(
            $this->getUser(),
            '/api/employees/999',
            Response::HTTP_NOT_FOUND
        );
    }

    public function test_it_preserves_other_employees_when_deleting(): void
    {
        $company = CompanyHelper::createWithEmployees(2);
        $employeeToDelete = $company->employees->first();
        $employeeToKeep = $company->employees->last();

        $this->deld(
            $this->getUser(),
            "/api/employees/{$employeeToDelete->id}",
            Response::HTTP_NO_CONTENT
        );

        $this->assertDatabaseMissing('employees', [
            'id' => $employeeToDelete->id,
        ]);

        $this->assertDatabaseHas('employees', [
            'id'         => $employeeToKeep->id,
            'first_name' => $employeeToKeep->first_name,
            'email'      => $employeeToKeep->email,
            'company_id' => $company->id,
        ]);
    }
}