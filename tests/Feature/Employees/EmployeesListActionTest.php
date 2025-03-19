<?php

declare(strict_types=1);

namespace Tests\Feature\Employees;

use Tests\Feature\ApiTest;
use Tests\Helpers\CompanyHelper;

class EmployeesListActionTest extends ApiTest
{
    public function test_it_returns_list_of_all_employees(): void
    {
        $company1 = CompanyHelper::createWithEmployees(2);
        $company2 = CompanyHelper::createWithEmployees(3);

        $response = $this->getd(
            $this->getUser(),
            '/api/employees'
        )->json('data');

        $this->assertCount(5, $response);

        $employee = $company1->employees->first();
        $responseEmployee = collect($response)->firstWhere('id', $employee->id);

        $this->assertEquals($employee->first_name, $responseEmployee['first_name']);
        $this->assertEquals($employee->last_name, $responseEmployee['last_name']);
        $this->assertEquals($employee->email, $responseEmployee['email']);
        $this->assertEquals($employee->phone, $responseEmployee['phone']);

        $responseIds = collect($response)->pluck('id')->toArray();
        $company1EmployeeIds = $company1->employees->pluck('id')->toArray();
        $company2EmployeeIds = $company2->employees->pluck('id')->toArray();
        $allEmployeeIds = array_merge($company1EmployeeIds, $company2EmployeeIds);

        sort($responseIds);
        sort($allEmployeeIds);
        $this->assertEquals($allEmployeeIds, $responseIds);
    }

    public function test_it_returns_empty_list_when_no_employees_exist(): void
    {
        $response = $this->getd(
            $this->getUser(),
            '/api/employees'
        )->json('data');

        $this->assertCount(0, $response);
    }

    public function test_it_includes_company_information_for_each_employee(): void
    {
        $company = CompanyHelper::createWithEmployees(1);

        $response = $this->getd(
            $this->getUser(),
            '/api/employees'
        )->json('data');

        $this->assertArrayHasKey('company', $response[0]);
        $this->assertEquals($company->id, $response[0]['company']['id']);
        $this->assertEquals($company->name, $response[0]['company']['name']);
        $this->assertEquals($company->nip, $response[0]['company']['nip']);
    }
}