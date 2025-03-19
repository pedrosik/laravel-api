<?php

declare(strict_types=1);

use App\Http\Actions\Companies;
use App\Http\Actions\Employees;
use Illuminate\Support\Facades\Route;

Route::prefix('companies')->group(function () {
    Route::post('/', Companies\CreateCompanyAction::class);
    Route::get('/', Companies\CompaniesListAction::class);

    Route::prefix('{company}')->group(function () {
        Route::get('/', Companies\ShowCompanyAction::class);
        Route::patch('/', Companies\UpdateCompanyAction::class);
        Route::delete('/', Companies\DeleteCompanyAction::class);

        Route::prefix('employees')->group(function () {
            Route::post('/', Companies\CreateEmployeeAction::class);
            Route::get('/', Companies\ListCompanyEmployeesAction::class);
       });
    });
});

Route::prefix('employees')->group(function () {
    Route::get('/', Employees\EmployeesListAction::class);

    Route::prefix('{employee}')->group(function () {
       Route::get('/', Employees\ShowEmployeeAction::class);
         Route::patch('/', Employees\UpdateEmployeeAction::class);
         Route::delete('/', Employees\DeleteEmployeeAction::class);
   });
});



