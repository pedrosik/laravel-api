<?php

namespace App\Http\Actions\Companies;

use App\Http\Resources\CompanyResource;
use App\Models\Company;

final readonly class ShowCompanyAction
{
    /**
     * Show a company
     *
     * @group Companies
     *
     * @response 200 storage/responses/companies/company.json
     */
    public function __invoke(Company $company): CompanyResource
    {
        return new CompanyResource($company->loadMissing('employees'));
    }
}