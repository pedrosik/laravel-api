<?php

declare(strict_types=1);

use App\Http\Actions\Companies;
//use App\Http\Actions\Employees;
use Illuminate\Support\Facades\Route;

Route::prefix('companies')->group(function () {
    Route::post('/', Companies\CreateCompanyAction::class);
    Route::get('/', Companies\CompaniesListAction::class);

    Route::prefix('{company}')->group(function () {
        Route::get('/', Companies\ShowCompanyAction::class);
        Route::patch('/', Companies\UpdateCompanyAction::class);
        Route::delete('/', Companies\DeleteCompanyAction::class);
    });
});
