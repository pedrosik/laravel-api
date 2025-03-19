<?php

namespace App\Http\Actions\Companies;

use App\Http\Requests\Company\CreateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Services\CompanyService;

class CreateCompanyAction
{
    public function __construct(private readonly CompanyService $companyService)
    {
    }

    /**
     * Create a new company
     *
     * @group Companies
     *
     * @response 200 storage/responses/company.json
     */
    public function __invoke(CreateCompanyRequest $request): CompanyResource
    {
        $company = $this->companyService->create($request->validated());

        return new CompanyResource($company->loadMissing('employees'));
    }
}