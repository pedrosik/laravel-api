<?php

namespace App\Http\Actions\Companies;

use App\Http\Resources\CompanyResource;
use App\Models\Company;

class ShowCompanyAction
{
    /**
     * Show a company
     *
     * @group Companies
     *
     * @response 200 storage/responses/company.json
     */
    public function __invoke(Company $company): CompanyResource
    {
        return new CompanyResource($company->loadMissing('employees'));
    }
}