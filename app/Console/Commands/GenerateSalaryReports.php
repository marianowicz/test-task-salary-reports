<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SalaryReports\Domain\DTO\SearchParams;
use SalaryReports\Domain\Exceptions\SalaryReportException;
use SalaryReports\Domain\SalaryReportsService;
use SalaryReports\Infrastructure\SalaryReportFormatterArray;

class GenerateSalaryReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salary-report:generate
                            {--search= : This string will be looked for in first name, last name and department name}
                            {--sort= : Sort by one of the following: FirstName, LastName, DepartmentName, BaseSalary, AllowanceAmount, AllowanceType, TotalSalary}
                            {--descending : Applied if --sort is set, ignored otherwise}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle(SalaryReportsService $salaryReportsService, SalaryReportFormatterArray $formatter)
    {
        /**
         * @TODO this command should be entirely within a SalaryReports module (in the Infrastructure part)
         */
        $searchParams = new SearchParams();
        if ($this->option('search')) {
            $searchParams->setSearch($this->option('search'));
        }
        if ($this->option('sort')) {
            $searchParams->setSortBy($this->option('sort'), $this->option('descending'));
        }

        try {
            $this->table(
                ['First name', 'Last name', 'Department', 'Base salary', 'Allowance amount', 'Allowance type', 'Total amount'],
                $formatter->format($salaryReportsService->generateReport($searchParams))
            );
        } catch (SalaryReportException $exception) {
            $this->error($exception->getMessage());
        }
    }
}
