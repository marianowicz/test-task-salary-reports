<?php

declare(strict_types=1);

namespace SalaryReports\Domain\Repositories;

use SalaryReports\Domain\DTO\SearchParams;
use SalaryReports\Domain\PayrollData;

interface PayrollRepositoryInterface
{
    public function getPayrollData(SearchParams $searchParams): PayrollData;
}
