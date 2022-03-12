<?php

declare(strict_types=1);

namespace SalaryReports\Domain\Repositories;

use SalaryReports\Domain\PayrollData;

interface PayrollRepositoryInterface
{
    public function getPayrollData(string $search = ''): PayrollData;
}
