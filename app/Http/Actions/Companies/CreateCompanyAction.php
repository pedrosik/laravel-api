<?php

namespace App\Http\Actions\Companies;

use App\Http\Requests\Companies\CreateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Services\CompanyService;

final readonly class CreateCompanyAction
{
    public function __construct(private CompanyService $companyService)
    {
    }

    /**
     * Create a new company
     *
     * @group Companies
     *
     * @response 200 storage/responses/companies/company.json
     */
    public function __invoke(CreateCompanyRequest $request): CompanyResource
    {
        $company = $this->companyService->create($request->validated());

        return new CompanyResource($company->loadMissing('employees'));
    }
}