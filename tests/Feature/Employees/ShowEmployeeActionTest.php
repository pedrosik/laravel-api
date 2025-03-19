<?php

declare(strict_types=1);

namespace Tests\Feature\Employees;

use Tests\Feature\ApiTest;
use Tests\Helpers\CompanyHelper;
use Symfony\Component\HttpFoundation\Response;

class ShowEmployeeActionTest extends ApiTest
{
    public function test_it_returns_employee_with_company(): void
    {
        $company = CompanyHelper::createWithEmployees(1);
        $employee = $company->employees->first();

        $response = $this->getd(
            $this->getUser(),
            "/api/employees/{$employee->id}"
        )->json('data');

        $this->assertEquals($employee->id, $response['id']);
        $this->assertEquals($employee->first_name, $response['first_name']);
        $this->assertEquals($employee->last_name, $response['last_name']);
        $this->assertEquals($employee->email, $response['email']);
        $this->assertEquals($employee->phone, $response['phone']);

        $this->assertArrayHasKey('company', $response);
        $this->assertEquals($company->id, $response['company']['id']);
        $this->assertEquals($company->name, $response['company']['name']);
        $this->assertEquals($company->nip, $response['company']['nip']);
        $this->assertEquals($company->address, $response['company']['address']);
        $this->assertEquals($company->city, $response['company']['city']);
        $this->assertEquals($company->post_code, $response['company']['post_code']);
    }

    public function test_it_returns_error_when_employee_not_found(): void
    {
        $this->getd(
            $this->getUser(),
            '/api/employees/999',
            Response::HTTP_NOT_FOUND
        );
    }
}