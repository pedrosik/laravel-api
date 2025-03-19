<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
class CompanyHelper
{
    public static function create(int $count = 1): Collection
    {
        return Company::factory()->count($count)->create();
    }

    public static function createWithEmployees(int $count = 2): Company
    {
        return Company::factory()->has(Employee::factory()->count($count))->create();
    }

    public static function generateRandomNIP(): string
    {
        $weights = [6, 5, 7, 2, 3, 4, 5, 6, 7];
        $nip = '';

        // 9 random digits
        for ($i = 0; $i < 9; $i++) {
            $nip .= mt_rand(0, 9);
        }

        // checksum
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += $nip[$i] * $weights[$i];
        }

        $checksum = $sum % 11;

        if ($checksum === 10) {
            $checksum = 0;
        }

        $nip .= $checksum;

        return $nip;
    }
}
