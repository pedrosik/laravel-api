<?php

declare(strict_types=1);

namespace Tests\Feature\Companies;

use Tests\Feature\ApiTest;
use Tests\Helpers\CompanyHelper;
use Symfony\Component\HttpFoundation\Response;

class ShowCompanyActionTest extends ApiTest
{
    public function test_it_returns_company_with_employees(): void
    {
        $company = CompanyHelper::createWithEmployees(2);

        $response = $this->getd($this->getUser(), "/api/companies/{$company->id}")->json('data');

        $this->assertEquals($company->name, $response['name']);
        $this->assertEquals($company->nip, $response['nip']);
        $this->assertEquals($company->address, $response['address']);
        $this->assertEquals($company->city, $response['city']);
        $this->assertEquals($company->post_code, $response['post_code']);
        $this->assertCount(2, $response['employees']);
    }

    public function test_it_returns_404_when_company_not_found(): void
    {
        $this->getd(
            $this->getUser(),
            '/api/companies/999',
            Response::HTTP_NOT_FOUND
        );
    }
}