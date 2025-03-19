<?php

namespace App\Http\Actions\Companies;

use App\Http\Resources\EmployeeResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final readonly class ListCompanyEmployeesAction
{
    public function __construct(private CompanyService $companyService)
    {
    }

    /**
     * List all employees for a company
     *
     * @group Company
     *
     * @response 200 storage/responses/employees/employee.json
     */
    public function __invoke(Company $company): AnonymousResourceCollection
    {
        return EmployeeResource::collection($this->companyService->listEmployees($company));
    }
}