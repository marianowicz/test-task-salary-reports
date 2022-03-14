<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SalaryReports\Domain\Repositories\PayrollRepositoryInterface;
use SalaryReports\Infrastructure\Repositories\IlluminateDatabasePayrollRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // @TODO this should be in a service provider within the SalaryReports module
        $this->app->bind(PayrollRepositoryInterface::class, IlluminateDatabasePayrollRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
