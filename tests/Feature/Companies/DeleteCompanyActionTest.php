<?php

declare(strict_types=1);

namespace Tests\Feature\Companies;

use Tests\Feature\ApiTest;
use Tests\Helpers\CompanyHelper;
use Symfony\Component\HttpFoundation\Response;

class DeleteCompanyActionTest extends ApiTest
{
    public function test_it_deletes_company_successfully(): void
    {
        $company = CompanyHelper::create();

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
        ]);

        $this->deld($this->getUser(), "/api/companies/{$company->id}", Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    }

    public function test_it_returns_404_when_company_does_not_exist(): void
    {
        $this->deld(
            $this->getUser(),
            '/api/companies/999999',
            Response::HTTP_NOT_FOUND
        );
    }

    public function test_it_deletes_company_with_employees(): void
    {
        $company = CompanyHelper::createWithEmployees(2);
        $employeeIds = $company->employees->pluck('id');

        $this->deld($this->getUser(), "/api/companies/{$company->id}", Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);

        $employeeIds->each(fn ($employeeId) => $this->assertDatabaseMissing('employees', [
            'id' => $employeeId,
        ]));
    }
}