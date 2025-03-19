<?php

declare(strict_types=1);

namespace Tests\Feature\Companies;

use Tests\Feature\ApiTest;
use Tests\Helpers\CompanyHelper;
use Symfony\Component\HttpFoundation\Response;

class UpdateCompanyActionTest extends ApiTest
{
    public function test_it_updates_company_successfully(): void
    {
        $company = CompanyHelper::create();

        $data = [
            'name'      => 'Updated Company Name',
            'nip'       => '9876543210',
            'address'   => 'Updated Street 123',
            'city'      => 'Updated City',
            'post_code' => '98-765',
        ];

        $response = $this->patchd(
            $this->getUser(),
            "/api/companies/{$company->id}",
            $data
        )->json('data');

        $this->assertEquals($data['name'], $response['name']);
        $this->assertEquals($data['nip'], $response['nip']);
        $this->assertEquals($data['address'], $response['address']);
        $this->assertEquals($data['city'], $response['city']);
        $this->assertEquals($data['post_code'], $response['post_code']);

        $this->assertDatabaseHas('companies', [
            'id'        => $company->id,
            'name'      => $data['name'],
            'nip'       => $data['nip'],
            'address'   => $data['address'],
            'city'      => $data['city'],
            'post_code' => $data['post_code'],
        ]);
    }

    public function test_it_returns_error_when_company_not_found(): void
    {
        $this->patchd(
            $this->getUser(),
            '/api/companies/999',
            ['name' => 'Test Company'],
            Response::HTTP_NOT_FOUND
        );
    }

    public function test_it_returns_error_when_nip_exists(): void
    {
        $company = CompanyHelper::create();
        $company2 = CompanyHelper::create();

        $response = $this->patchd(
            $this->getUser(),
            "/api/companies/{$company->id}",
            ['nip' => $company2->nip],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors([
            'nip' => __('validation.unique', ['attribute' => 'nip']),
        ]);

        $this->assertDatabaseHas('companies', [
            'id'  => $company->id,
            'nip' => $company->nip,
        ]);

        $this->assertDatabaseHas('companies', [
            'id'  => $company2->id,
            'nip' => $company2->nip,
        ]);
    }

    public function test_it_returns_error_when_nip_is_invalid(): void
    {
        $company = CompanyHelper::create();

        $data = [
            'name'      => 'Test Company',
            'nip'       => '12345', // Invalid NIP
            'address'   => 'Test Street',
            'city'      => 'Test City',
            'post_code' => '12-345',
        ];

        $response = $this->patchd(
            $this->getUser(),
            "/api/companies/{$company->id}",
            $data,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors([
            'nip' => __('validation.custom.nip_invalid'),
        ]);

        $this->assertDatabaseMissing('companies', [
            'id'  => $company->id,
            'nip' => $data['nip'],
        ]);
    }

    public function test_it_preserves_employees_relationship_after_update(): void
    {
        $company = CompanyHelper::createWithEmployees(2);
        $employeeIds = $company->employees->pluck('id')->toArray();

        $data = [
            'name'      => 'Updated Company Name',
            'nip'       => '9876543210',
            'address'   => 'Updated Street 123',
            'city'      => 'Updated City',
            'post_code' => '98-765',
        ];

        $response = $this->patchd(
            $this->getUser(),
            "/api/companies/{$company->id}",
            $data
        )->json('data');

        $this->assertCount(2, $response['employees']);
        $this->assertEquals(
            $employeeIds,
            collect($response['employees'])->pluck('id')->toArray()
        );
    }
}