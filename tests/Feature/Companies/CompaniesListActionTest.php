<?php

declare(strict_types=1);

namespace Tests\Feature\Companies;

use Tests\Feature\ApiTest;
use Tests\Helpers\CompanyHelper;

class CompaniesListActionTest extends ApiTest
{
    public function test_it_returns_empty_list_when_no_companies_exist(): void
    {
        $response = $this->getd($this->getUser(), '/api/companies')->json('data');

        $this->assertCount(0, $response);
    }

    public function test_it_returns_list_of_companies_with_employees(): void
    {
        CompanyHelper::createWithEmployees(2);
        CompanyHelper::create(2);

        $response = $this->getd($this->getUser(), '/api/companies')->json('data');

        $this->assertCount(3, $response);
        $this->assertCount(2, $response[0]['employees']);
    }
}