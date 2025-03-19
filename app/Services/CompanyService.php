<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(Company $company, array $data): Company
    {
        $company->update($data);

        return $company;
    }

    public function delete(Company $company): void
    {
        $company->delete();
    }
}
