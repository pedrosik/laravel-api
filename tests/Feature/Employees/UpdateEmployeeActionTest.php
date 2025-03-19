<?php

declare(strict_types=1);

namespace Tests\Feature\Employees;

use Tests\Feature\ApiTest;
use Tests\Helpers\CompanyHelper;
use Symfony\Component\HttpFoundation\Response;

class UpdateEmployeeActionTest extends ApiTest
{
    public function test_it_updates_employee_successfully(): void
    {
        $company = CompanyHelper::createWithEmployees(1);
        $employee = $company->employees->first();

        $data = [
            'first_name' => 'Updated First Name',
            'last_name'  => 'Updated Last Name',
            'email'      => 'updated.email@example.com',
            'phone'      => '+48987654321',
        ];

        $response = $this->patchd(
            $this->getUser(),
            "/api/employees/{$employee->id}",
            $data
        )->json('data');

        $this->assertEquals($data['first_name'], $response['first_name']);
        $this->assertEquals($data['last_name'], $response['last_name']);
        $this->assertEquals($data['email'], $response['email']);
        $this->assertEquals($data['phone'], $response['phone']);

        $this->assertArrayHasKey('company', $response);
        $this->assertEquals($company->id, $response['company']['id']);

        $this->assertDatabaseHas('employees', [
            'id'         => $employee->id,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'company_id' => $company->id,
        ]);
    }

    public function test_it_returns_error_when_employee_not_found(): void
    {
        $this->patchd(
            $this->getUser(),
            '/api/employees/999',
            ['first_name' => 'John'],
            Response::HTTP_NOT_FOUND
        );
    }

    public function test_it_returns_error_when_email_exists(): void
    {
        $company = CompanyHelper::createWithEmployees(2);
        $employee1 = $company->employees->first();
        $employee2 = $company->employees->last();

        $data = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => $employee2->email,
            'phone'      => '+48123456789',
        ];

        $response = $this->patchd(
            $this->getUser(),
            "/api/employees/{$employee1->id}",
            $data,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors([
            'email' => __('validation.unique', ['attribute' => 'email']),
        ]);

        $this->assertDatabaseMissing('employees', [
            'id'    => $employee1->id,
            'email' => $data['email'],
        ]);
    }

    public function test_it_returns_error_when_email_is_invalid(): void
    {
        $company = CompanyHelper::createWithEmployees(1);
        $employee = $company->employees->first();

        $data = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'invalid-email',
            'phone'      => '+48123456789',
        ];

        $response = $this->patchd(
            $this->getUser(),
            "/api/employees/{$employee->id}",
            $data,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors([
            'email' => __('validation.email', ['attribute' => 'email']),
        ]);

        $this->assertDatabaseMissing('employees', [
            'id'    => $employee->id,
            'email' => $data['email'],
        ]);
    }
}