<?php

declare(strict_types=1);

namespace Tests\Feature\Companies;

use Tests\Feature\ApiTest;
use Tests\Helpers\CompanyHelper;
use Symfony\Component\HttpFoundation\Response;

class CreateEmployeeActionTest extends ApiTest
{
    public function test_it_creates_employee_successfully(): void
    {
        $company = CompanyHelper::create();

        $data = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'john.doe@example.com',
            'phone'      => '+48123456789',
        ];

        $response = $this->postd(
            $this->getUser(),
            "/api/companies/{$company->id}/employees",
            $data,
            Response::HTTP_CREATED
        )->json('data');

        $this->assertEquals($data['first_name'], $response['first_name']);
        $this->assertEquals($data['last_name'], $response['last_name']);
        $this->assertEquals($data['email'], $response['email']);
        $this->assertEquals($data['phone'], $response['phone']);
        $this->assertEquals($company->id, $response['company']['id']);

        $this->assertDatabaseHas('employees', [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'company_id' => $company->id,
        ]);
    }

    public function test_it_returns_error_when_company_not_found(): void
    {
        $this->postd(
            $this->getUser(),
            '/api/companies/999/employees',
            ['first_name' => 'John'],
            Response::HTTP_NOT_FOUND
        );
    }

    public function test_it_returns_error_when_email_exists(): void
    {
        $company = CompanyHelper::createWithEmployees(1);
        $existingEmployee = $company->employees->first();

        $data = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => $existingEmployee->email,
            'phone'      => '+48123456789',
        ];

        $response = $this->postd(
            $this->getUser(),
            "/api/companies/{$company->id}/employees",
            $data,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors([
            'email' => __('validation.unique', ['attribute' => 'email']),
        ]);

        $this->assertDatabaseMissing('employees', [
            'first_name' => $data['first_name'],
            'email'      => $data['email'],
        ]);
    }

    public function test_it_returns_error_when_required_fields_are_missing(): void
    {
        $company = CompanyHelper::create();

        $response = $this->postd(
            $this->getUser(),
            "/api/companies/{$company->id}/employees",
            [],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors([
            'first_name' => __('validation.required', ['attribute' => 'first name']),
            'last_name'  => __('validation.required', ['attribute' => 'last name']),
            'email'      => __('validation.required', ['attribute' => 'email']),
        ]);
    }

    public function test_it_returns_error_when_email_is_invalid(): void
    {
        $company = CompanyHelper::create();

        $data = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'invalid-email',
            'phone'      => '+48123456789',
        ];

        $response = $this->postd(
            $this->getUser(),
            "/api/companies/{$company->id}/employees",
            $data,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors([
            'email' => __('validation.email', ['attribute' => 'email']),
        ]);

        $this->assertDatabaseMissing('employees', [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
        ]);
    }
}