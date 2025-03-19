<?php

namespace App\Http\Actions\Companies;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final readonly class CompaniesListAction
{
    /**
     * List all companies
     *
     * @group Companies
     *
     * @response 200 storage/responses/companies/companies.json
     */
    public function __invoke(): AnonymousResourceCollection
    {
        return CompanyResource::collection(Company::all()->loadMissing('employees'));
    }
}