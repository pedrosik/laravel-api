<?php

namespace App\Http\Actions\Companies;

use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;

class UpdateCompanyAction
{
    public function __construct(private readonly CompanyService $companyService)
    {
    }

    /**
     * Update a company
     *
     * @group Companies
     *
     * @response 200 storage/responses/company.json
     */
    public function __invoke(UpdateCompanyRequest $request, Company $company): CompanyResource
    {
        $company = $this->companyService->update($company, $request->validated());

        return new CompanyResource($company->loadMissing('employees'));
    }
}