<?php

declare(strict_types=1);

namespace Tests\Feature\Companies;

use Tests\Feature\ApiTest;
use Tests\Helpers\CompanyHelper;
use Symfony\Component\HttpFoundation\Response;

class ListCompanyEmployeesActionTest extends ApiTest
{
    public function test_it_returns_list_of_employees(): void
    {
        $company = CompanyHelper::createWithEmployees(3);

        $response = $this->getd(
            $this->getUser(),
            "/api/companies/{$company->id}/employees"
        )->json('data');

        $this->assertCount(3, $response);

        $employee = $company->employees->first();

        $this->assertEquals($employee->id, $response[0]['id']);
        $this->assertEquals($employee->first_name, $response[0]['first_name']);
        $this->assertEquals($employee->last_name, $response[0]['last_name']);
        $this->assertEquals($employee->email, $response[0]['email']);
        $this->assertEquals($employee->phone, $response[0]['phone']);
    }

    public function test_it_returns_empty_list_when_company_has_no_employees(): void
    {
        $company = CompanyHelper::create();

        $response = $this->getd(
            $this->getUser(),
            "/api/companies/{$company->id}/employees"
        )->json('data');

        $this->assertCount(0, $response);
    }

    public function test_it_returns_error_when_company_not_found(): void
    {
        $this->getd(
            $this->getUser(),
            '/api/companies/999/employees',
            Response::HTTP_NOT_FOUND
        );
    }

    public function test_it_returns_only_employees_for_requested_company(): void
    {
        $company = CompanyHelper::createWithEmployees(2);
        CompanyHelper::createWithEmployees(3);

        $response = $this->getd(
            $this->getUser(),
            "/api/companies/{$company->id}/employees"
        )->json('data');

        $this->assertCount(2, $response);

        $responseIds = collect($response)->pluck('id')->toArray();
        $companyEmployeeIds = $company->employees->pluck('id')->toArray();
        sort($responseIds);
        sort($companyEmployeeIds);
        $this->assertEquals($companyEmployeeIds, $responseIds);
    }
}