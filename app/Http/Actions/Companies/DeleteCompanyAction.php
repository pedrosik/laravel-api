<?php

namespace App\Http\Actions\Companies;

use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;

final readonly class DeleteCompanyAction
{
    public function __construct(private CompanyService $companyService)
    {
    }

    /**
     * Delete a company
     *
     * @group Companies
     *
     * @response 204
     */
    public function __invoke(Company $company): JsonResponse
    {
        $this->companyService->delete($company);

        return new JsonResponse([], 204);
    }
}