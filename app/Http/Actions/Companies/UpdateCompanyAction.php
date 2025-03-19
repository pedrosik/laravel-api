<?php

namespace App\Http\Actions\Companies;

use App\Http\Requests\Companies\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;

final readonly class UpdateCompanyAction
{
    public function __construct(private CompanyService $companyService)
    {
    }

    /**
     * Update a company
     *
     * @group Companies
     *
     * @response 200 storage/responses/companies/company.json
     */
    public function __invoke(UpdateCompanyRequest $request, Company $company): CompanyResource
    {
        $company = $this->companyService->update($company, $request->validated());

        return new CompanyResource($company->loadMissing('employees'));
    }
}